<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\UserConsent;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    /**
     * Kullanıcı oluştur
     *
     * @param array $data
     * @return User
     */
    public function create(array $data): User
    {
        // Şifreyi hash'le
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return User::create($data);
    }

    /**
     * ID ile kullanıcı bul
     * Performans: Eager loading desteği ile N+1 problemi önlenir
     *
     * @param int $id
     * @param array $relations
     * @return User|null
     */
    public function findById(int $id, array $relations = []): ?User
    {
        $query = User::query();

        if (!empty($relations)) {
            $query->with($relations);
        }

        return $query->find($id);
    }

    /**
     * Email ile kullanıcı bul
     * Performans: Eager loading desteği
     *
     * @param string $email
     * @param array $relations
     * @return User|null
     */
    public function findByEmail(string $email, array $relations = []): ?User
    {
        $query = User::where('email', $email);

        if (!empty($relations)) {
            $query->with($relations);
        }

        return $query->first();
    }

    /**
     * Kullanıcı güncelle
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        $user = User::find($id);

        if (!$user) {
            return false;
        }

        // Şifre varsa hash'le
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return $user->update($data);
    }

    /**
     * Token bakiyesi güncelle
     *
     * @param int $userId
     * @param float $amount
     * @return bool
     */
    public function updateTokenBalance(int $userId, float $amount): bool
    {
        $user = User::find($userId);

        if (!$user) {
            return false;
        }

        return $user->update(['token_balance' => $amount]);
    }

    /**
     * Tüm admin kullanıcıları getir
     * Performans: Scope kullanımı + eager loading
     *
     * @param array $relations
     * @return Collection
     */
    public function getAllAdmins(array $relations = []): Collection
    {
        $query = User::admins();

        if (!empty($relations)) {
            $query->with($relations);
        }

        return $query->get();
    }

    /**
     * Tüm normal kullanıcıları getir
     * Performans: Scope kullanımı + eager loading
     *
     * @param array $relations
     * @return Collection
     */
    public function getAllNormalUsers(array $relations = []): Collection
    {
        $query = User::normalUsers();

        if (!empty($relations)) {
            $query->with($relations);
        }

        return $query->get();
    }

    /**
     * Kullanıcı + onay kaydı birlikte oluştur
     * Performans: Transaction kullanarak atomik işlem + rollback güvenliği
     *
     * @param array $userData
     * @param array $consentData
     * @return User
     * @throws \Exception
     */
    public function createWithConsent(array $userData, array $consentData): User
    {
        return DB::transaction(function () use ($userData, $consentData) {
            // Kullanıcıyı oluştur
            $user = $this->create($userData);

            // Onay kaydını oluştur
            $consentData['user_id'] = $user->id;
            $consentData['accepted_at'] = now();
            UserConsent::create($consentData);

            // İlişkiyi eager load et (N+1 önleme)
            $user->load('consent');

            return $user;
        });
    }

    /**
     * Telefon numarasına göre kullanıcı bul
     *
     * @param string $phone
     * @return User|null
     */
    public function findByPhone(string $phone): ?User
    {
        return User::where('phone', $phone)->first();
    }

    /**
     * Kullanıcıyı sil
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $user = User::find($id);

        if (!$user) {
            return false;
        }

        return $user->delete();
    }
}
