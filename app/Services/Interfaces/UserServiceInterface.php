<?php

namespace App\Services\Interfaces;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserServiceInterface
{
    /**
     * Yeni kullanıcı kaydı oluştur (onaylar ile birlikte)
     *
     * @param array $data
     * @return User
     */
    public function register(array $data): User;

    /**
     * Kullanıcı girişi
     *
     * @param string $email
     * @param string $password
     * @return User|null
     */
    public function login(string $email, string $password): ?User;

    /**
     * Kullanıcı bilgilerini getir
     *
     * @param int $userId
     * @param array $relations
     * @return User|null
     */
    public function getUserById(int $userId, array $relations = []): ?User;

    /**
     * Kullanıcı bilgilerini güncelle
     *
     * @param int $userId
     * @param array $data
     * @return bool
     */
    public function updateUser(int $userId, array $data): bool;

    /**
     * Token ekle (satın alma veya admin tanımlama)
     *
     * @param int $userId
     * @param float $amount
     * @param string $description
     * @return bool
     */
    public function addTokens(int $userId, float $amount, string $description): bool;

    /**
     * Token düş (talep tamamlama)
     *
     * @param int $userId
     * @param float $amount
     * @param string $description
     * @return bool
     */
    public function deductTokens(int $userId, float $amount, string $description): bool;

    /**
     * Tüm admin kullanıcıları getir
     *
     * @return Collection
     */
    public function getAllAdmins(): Collection;

    /**
     * Tüm normal kullanıcıları getir
     *
     * @return Collection
     */
    public function getAllNormalUsers(): Collection;

    /**
     * Admin kullanıcı oluştur
     *
     * @param array $data
     * @return User
     */
    public function createAdmin(array $data): User;

    /**
     * Kullanıcı sil
     *
     * @param int $userId
     * @return bool
     */
    public function deleteUser(int $userId): bool;

    /**
     * Email benzersizlik kontrolü
     *
     * @param string $email
     * @param int|null $exceptUserId
     * @return bool
     */
    public function isEmailUnique(string $email, ?int $exceptUserId = null): bool;

    /**
     * Telefon benzersizlik kontrolü
     *
     * @param string $phone
     * @param int|null $exceptUserId
     * @return bool
     */
    public function isPhoneUnique(string $phone, ?int $exceptUserId = null): bool;
}
