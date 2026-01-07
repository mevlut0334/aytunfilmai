<?php

namespace App\Services\Interfaces;

use App\Models\Package;
use Illuminate\Database\Eloquent\Collection;

interface PackageServiceInterface
{
    /**
     * Tüm paketleri getir
     *
     * @return Collection
     */
    public function getAllPackages(): Collection;

    /**
     * Sadece aktif paketleri getir (müşterilere gösterilecek)
     *
     * @return Collection
     */
    public function getActivePackages(): Collection;

    /**
     * ID ile paket getir
     *
     * @param int $id
     * @return Package|null
     */
    public function getPackageById(int $id): ?Package;

    /**
     * Paket oluştur (Admin)
     *
     * @param array $data
     * @return Package
     */
    public function createPackage(array $data): Package;

    /**
     * Paket güncelle (Admin)
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updatePackage(int $id, array $data): bool;

    /**
     * Paket sil (Admin)
     *
     * @param int $id
     * @return bool
     */
    public function deletePackage(int $id): bool;

    /**
     * Paketi aktif/pasif yap (Admin)
     *
     * @param int $id
     * @param bool $isActive
     * @return bool
     */
    public function togglePackageStatus(int $id, bool $isActive): bool;

    /**
     * Paket satış istatistikleri
     *
     * @param int $packageId
     * @return array
     */
    public function getPackageStats(int $packageId): array;
}
