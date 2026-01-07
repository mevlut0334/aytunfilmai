<?php

namespace App\Services;

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

    /**
     * Dependency Injection
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Yeni kullanıcı kaydı oluştur (onaylar ile birlikte)
     * Performans: Transaction kullanarak atomik işlem
     *
     * @param array $data
     * @return User
     * @throws \Exception
     */
    public function register(array $data): User
    {
        // Kullanıcı verilerini hazırla
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => $data['password'],
            'is_admin' => false, // Normal kullanıcı
            'token_balance' => 0,
        ];

        // Onay verilerini hazırla
        $consentData = [
            'terms_accepted' => $data['terms_accepted'] ?? false,
            'copyright_accepted' => $data['copyright_accepted'] ?? false,
            'kvkk_accepted' => $data['kvkk_accepted'] ?? false,
            'personal_data_accepted' => $data['personal_data_accepted'] ?? false,
            'ip_address' => $data['ip_address'] ?? request()->ip(),
            'user_agent' => $data['user_agent'] ?? request()->userAgent(),
        ];

        // Repository ile kullanıcı + onay oluştur
        return $this->userRepository->createWithConsent($userData, $consentData);
    }

    /**
     * Kullanıcı girişi
     *
     * @param string $email
     * @param string $password
     * @return User|null
     */
    public function login(string $email, string $password): ?User
    {
        $user = $this->userRepository->findByEmail($email);

        if (!$user) {
            return null;
        }

        // Şifre kontrolü
        if (!Hash::check($password, $user->password)) {
            return null;
        }

        // Laravel Auth login
        Auth::login($user);

        return $user;
    }

    /**
     * Kullanıcı bilgilerini getir
     * Performans: Eager loading desteği
     *
     * @param int $userId
     * @param array $relations
     * @return User|null
     */
    public function getUserById(int $userId, array $relations = []): ?User
    {
        return $this->userRepository->findById($userId, $relations);
    }

    /**
     * Kullanıcı bilgilerini güncelle
     *
     * @param int $userId
     * @param array $data
     * @return bool
     */
    public function updateUser(int $userId, array $data): bool
    {
        return $this->userRepository->update($userId, $data);
    }

    /**
     * Token ekle (satın alma veya admin tanımlama)
     * Performans: Transaction kullanarak atomik işlem
     *
     * @param int $userId
     * @param float $amount
     * @param string $description
     * @return bool
     * @throws \Exception
     */
    public function addTokens(int $userId, float $amount, string $description): bool
    {
        return DB::transaction(function () use ($userId, $amount, $description) {
            $user = $this->userRepository->findById($userId);

            if (!$user) {
                return false;
            }

            // Yeni token bakiyesi
            $newBalance = $user->token_balance + $amount;

            // Bakiyeyi güncelle
            $updated = $this->userRepository->updateTokenBalance($userId, $newBalance);

            if ($updated) {
                // TODO: TokenTransaction modeli oluşturulunca buraya transaction kaydı eklenecek
                // TokenTransaction::create([
                //     'user_id' => $userId,
                //     'amount' => $amount,
                //     'type' => 'credit',
                //     'description' => $description,
                //     'balance_after' => $newBalance,
                // ]);
            }

            return $updated;
        });
    }

    /**
     * Token düş (talep tamamlama)
     * Performans: Transaction kullanarak atomik işlem
     *
     * @param int $userId
     * @param float $amount
     * @param string $description
     * @return bool
     * @throws \Exception
     */
    public function deductTokens(int $userId, float $amount, string $description): bool
    {
        return DB::transaction(function () use ($userId, $amount, $description) {
            $user = $this->userRepository->findById($userId);

            if (!$user) {
                return false;
            }

            // Yeterli token kontrolü
            if ($user->token_balance < $amount) {
                return false;
            }

            // Yeni token bakiyesi
            $newBalance = $user->token_balance - $amount;

            // Bakiyeyi güncelle
            $updated = $this->userRepository->updateTokenBalance($userId, $newBalance);

            if ($updated) {
                // TODO: TokenTransaction modeli oluşturulunca buraya transaction kaydı eklenecek
                // TokenTransaction::create([
                //     'user_id' => $userId,
                //     'amount' => $amount,
                //     'type' => 'debit',
                //     'description' => $description,
                //     'balance_after' => $newBalance,
                // ]);
            }

            return $updated;
        });
    }

    /**
     * Tüm admin kullanıcıları getir
     *
     * @return Collection
     */
    public function getAllAdmins(): Collection
    {
        return $this->userRepository->getAllAdmins();
    }

    /**
     * Tüm normal kullanıcıları getir
     *
     * @return Collection
     */
    public function getAllNormalUsers(): Collection
    {
        return $this->userRepository->getAllNormalUsers();
    }

    /**
     * Admin kullanıcı oluştur
     *
     * @param array $data
     * @return User
     */
    public function createAdmin(array $data): User
    {
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => $data['password'],
            'is_admin' => true, // Admin kullanıcı
            'token_balance' => 0,
        ];

        return $this->userRepository->create($userData);
    }

    /**
     * Kullanıcı sil
     *
     * @param int $userId
     * @return bool
     */
    public function deleteUser(int $userId): bool
    {
        return $this->userRepository->delete($userId);
    }

    /**
     * Email benzersizlik kontrolü
     *
     * @param string $email
     * @param int|null $exceptUserId
     * @return bool
     */
    public function isEmailUnique(string $email, ?int $exceptUserId = null): bool
    {
        $user = $this->userRepository->findByEmail($email);

        if (!$user) {
            return true;
        }

        // Güncelleme sırasında kendi emailini kontrol ediyor
        if ($exceptUserId && $user->id === $exceptUserId) {
            return true;
        }

        return false;
    }

    /**
     * Telefon benzersizlik kontrolü
     *
     * @param string $phone
     * @param int|null $exceptUserId
     * @return bool
     */
    public function isPhoneUnique(string $phone, ?int $exceptUserId = null): bool
    {
        $user = $this->userRepository->findByPhone($phone);

        if (!$user) {
            return true;
        }

        // Güncelleme sırasında kendi telefonunu kontrol ediyor
        if ($exceptUserId && $user->id === $exceptUserId) {
            return true;
        }

        return false;
    }
}
