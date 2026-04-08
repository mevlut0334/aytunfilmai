<?php

namespace App\Services\Interfaces;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserServiceInterface
{
    public function register(array $data): User;

    public function login(string $email, string $password): ?User;

    public function getUserById(int $userId, array $relations = []): ?User;

    public function updateUser(int $userId, array $data): bool;

    /**
     * Token ekle (satın alma veya admin tanımlama)
     */
    public function addTokens(int $userId, float $amount, string $description): bool;

    /**
     * Token düş (talep tamamlama)
     */
    public function deductTokens(int $userId, float $amount, string $description): bool;

    /**
     * Abonelik yenilemesi: bakiyeyi sıfırla + yeni token yükle
     */
    public function resetAndAddTokens(int $userId, float $amount, string $description, ?int $orderId = null): bool;

    /**
     * Abonelik sona erdi: bakiyeyi sıfırla
     */
    public function clearTokens(int $userId): bool;

    public function getAllAdmins(): Collection;

    public function getAllNormalUsers(): Collection;

    public function createAdmin(array $data): User;

    public function deleteUser(int $userId): bool;

    public function isEmailUnique(string $email, ?int $exceptUserId = null): bool;

    public function isPhoneUnique(string $phone, ?int $exceptUserId = null): bool;
}
