<?php

namespace App\Services;

use App\Models\TokenTransaction;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Interfaces\UserServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService implements UserServiceInterface
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(array $data): User
    {
        $userData = [
            'name'                    => $data['name'],
            'email'                   => $data['email'],
            'phone'                   => $data['phone'],
            'password'                => $data['password'],
            'is_admin'                => false,
            'token_balance'           => 0,
        ];

        $consentData = [
            'terms_accepted'          => $data['terms_accepted']          ?? false,
            'copyright_accepted'      => $data['copyright_accepted']      ?? false,
            'kvkk_accepted'           => $data['kvkk_accepted']           ?? false,
            'personal_data_accepted'  => $data['personal_data_accepted']  ?? false,
            'ip_address'              => $data['ip_address']              ?? request()->ip(),
            'user_agent'              => $data['user_agent']              ?? request()->userAgent(),
        ];

        return $this->userRepository->createWithConsent($userData, $consentData);
    }

    public function login(string $email, string $password): ?User
    {
        $user = $this->userRepository->findByEmail($email);

        if (!$user || !Hash::check($password, $user->password)) {
            return null;
        }

        Auth::login($user);

        return $user;
    }

    public function getUserById(int $userId, array $relations = []): ?User
    {
        return $this->userRepository->findById($userId, $relations);
    }

    public function updateUser(int $userId, array $data): bool
    {
        return $this->userRepository->update($userId, $data);
    }

    /**
     * Token ekle (satın alma veya admin tanımlama)
     * TokenTransaction kaydı oluşturur.
     */
    public function addTokens(int $userId, float $amount, string $description): bool
    {
        return DB::transaction(function () use ($userId, $amount, $description) {
            $user = $this->userRepository->findById($userId);

            if (!$user) {
                return false;
            }

            $newBalance = $user->token_balance + $amount;
            $updated    = $this->userRepository->updateTokenBalance($userId, $newBalance);

            if ($updated) {
                TokenTransaction::create([
                    'user_id'       => $userId,
                    'amount'        => $amount,
                    'type'          => 'credit',
                    'description'   => $description,
                    'balance_after' => $newBalance,
                ]);
            }

            return $updated;
        });
    }

    /**
     * Token düş (talep tamamlama)
     * TokenTransaction kaydı oluşturur.
     */
    public function deductTokens(int $userId, float $amount, string $description): bool
    {
        return DB::transaction(function () use ($userId, $amount, $description) {
            $user = $this->userRepository->findById($userId);

            if (!$user) {
                return false;
            }

            if ($user->token_balance < $amount) {
                return false;
            }

            $newBalance = $user->token_balance - $amount;
            $updated    = $this->userRepository->updateTokenBalance($userId, $newBalance);

            if ($updated) {
                TokenTransaction::create([
                    'user_id'       => $userId,
                    'amount'        => $amount,
                    'type'          => 'debit',
                    'description'   => $description,
                    'balance_after' => $newBalance,
                ]);
            }

            return $updated;
        });
    }

    /**
     * Abonelik yenilemesi: mevcut bakiyeyi sıfırla, paketteki token'ı yükle.
     * Spotify mantığı — eski bakiye silinir, yeni dönem başlar.
     *
     * @param int    $userId
     * @param float  $amount       Paketteki token miktarı
     * @param string $description  Yükleme açıklaması
     * @param int|null $orderId    İlişkili sipariş ID
     */
    public function resetAndAddTokens(int $userId, float $amount, string $description, ?int $orderId = null): bool
    {
        return DB::transaction(function () use ($userId, $amount, $description, $orderId) {
            $user = $this->userRepository->findById($userId);

            if (!$user) {
                return false;
            }

            $oldBalance = (float) $user->token_balance;

            // Bakiyeyi direkt paketteki miktar ile değiştir
            $updated = $this->userRepository->updateTokenBalance($userId, $amount);

            if ($updated) {
                // Eski bakiye varsa sıfırlama kaydı
                if ($oldBalance > 0) {
                    TokenTransaction::create([
                        'user_id'       => $userId,
                        'amount'        => $oldBalance,
                        'type'          => 'subscription_reset',
                        'description'   => 'Abonelik yenilendi — önceki bakiye sıfırlandı',
                        'order_id'      => $orderId,
                        'balance_after' => 0,
                    ]);
                }

                // Yeni token yükleme kaydı
                TokenTransaction::create([
                    'user_id'       => $userId,
                    'amount'        => $amount,
                    'type'          => 'credit',
                    'description'   => $description,
                    'order_id'      => $orderId,
                    'balance_after' => $amount,
                ]);
            }

            return $updated;
        });
    }

    /**
     * Abonelik sona erince token bakiyesini sıfırla.
     *
     * @param int $userId
     */
    public function clearTokens(int $userId): bool
    {
        return DB::transaction(function () use ($userId) {
            $user = $this->userRepository->findById($userId);

            if (!$user) {
                return false;
            }

            $oldBalance = (float) $user->token_balance;

            $updated = $this->userRepository->updateTokenBalance($userId, 0);

            if ($updated && $oldBalance > 0) {
                TokenTransaction::create([
                    'user_id'       => $userId,
                    'amount'        => $oldBalance,
                    'type'          => 'subscription_reset',
                    'description'   => 'Abonelik sona erdi — bakiye sıfırlandı',
                    'order_id'      => null,
                    'balance_after' => 0,
                ]);
            }

            return $updated;
        });
    }

    public function getAllAdmins(): Collection
    {
        return $this->userRepository->getAllAdmins();
    }

    public function getAllNormalUsers(): Collection
    {
        return $this->userRepository->getAllNormalUsers();
    }

    public function createAdmin(array $data): User
    {
        $userData = [
            'name'          => $data['name'],
            'email'         => $data['email'],
            'phone'         => $data['phone'],
            'password'      => $data['password'],
            'is_admin'      => true,
            'token_balance' => 0,
        ];

        return $this->userRepository->create($userData);
    }

    public function deleteUser(int $userId): bool
    {
        return $this->userRepository->delete($userId);
    }

    public function isEmailUnique(string $email, ?int $exceptUserId = null): bool
    {
        $user = $this->userRepository->findByEmail($email);

        if (!$user) return true;
        if ($exceptUserId && $user->id === $exceptUserId) return true;

        return false;
    }

    public function isPhoneUnique(string $phone, ?int $exceptUserId = null): bool
    {
        $user = $this->userRepository->findByPhone($phone);

        if (!$user) return true;
        if ($exceptUserId && $user->id === $exceptUserId) return true;

        return false;
    }
}
