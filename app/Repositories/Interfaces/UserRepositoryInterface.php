<?php

namespace App\Repositories\Interfaces;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface
{
    /**
     * Kullanıcı oluştur
     *
     * @param array $data
     * @return User
     */
    public function create(array $data): User;

    /**
     * ID ile kullanıcı bul
     *
     * @param int $id
     * @param array $relations Eager loading için ilişkiler
     * @return User|null
     */
    public function findById(int $id, array $relations = []): ?User;

    /**
     * Email ile kullanıcı bul
     *
     * @param string $email
     * @param array $relations Eager loading için ilişkiler
     * @return User|null
     */
    public function findByEmail(string $email, array $relations = []): ?User;

    /**
     * Kullanıcı güncelle
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool;

    /**
     * Token bakiyesi güncelle
     *
     * @param int $userId
     * @param float $amount
     * @return bool
     */
    public function updateTokenBalance(int $userId, float $amount): bool;

    /**
     * Tüm admin kullanıcıları getir
     *
     * @param array $relations Eager loading için ilişkiler
     * @return Collection
     */
    public function getAllAdmins(array $relations = []): Collection;

    /**
     * Tüm normal kullanıcıları getir
     *
     * @param array $relations Eager loading için ilişkiler
     * @return Collection
     */
    public function getAllNormalUsers(array $relations = []): Collection;

    /**
     * Kullanıcı + onay kaydı birlikte oluştur (Transaction içinde)
     * Performans: Tek transaction ile her iki kaydı birden oluşturur
     *
     * @param array $userData
     * @param array $consentData
     * @return User
     */
    public function createWithConsent(array $userData, array $consentData): User;

    /**
     * Telefon numarasına göre kullanıcı bul
     *
     * @param string $phone
     * @return User|null
     */
    public function findByPhone(string $phone): ?User;

    /**
     * Kullanıcıyı sil
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}
