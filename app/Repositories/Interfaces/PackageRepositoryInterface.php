<?php

namespace App\Repositories\Interfaces;

use App\Models\Package;
use Illuminate\Database\Eloquent\Collection;

interface PackageRepositoryInterface
{
    /**
     * Tüm paketleri getir
     *
     * @param array $relations Eager loading için ilişkiler
     * @return Collection
     */
    public function getAll(array $relations = []): Collection;

    /**
     * Sadece aktif paketleri getir (sıralı)
     *
     * @param array $relations Eager loading için ilişkiler
     * @return Collection
     */
    public function getActivePackages(array $relations = []): Collection;

    /**
     * ID ile paket bul
     *
     * @param int $id
     * @param array $relations Eager loading için ilişkiler
     * @return Package|null
     */
    public function findById(int $id, array $relations = []): ?Package;

    /**
     * Paket oluştur
     *
     * @param array $data
     * @return Package
     */
    public function create(array $data): Package;

    /**
     * Paket güncelle
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool;

    /**
     * Paket sil
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * Paketi aktif/pasif yap
     *
     * @param int $id
     * @param bool $isActive
     * @return bool
     */
    public function toggleActive(int $id, bool $isActive): bool;
}
