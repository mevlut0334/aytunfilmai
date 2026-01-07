<?php

namespace App\Repositories;

use App\Models\Package;
use App\Repositories\Interfaces\PackageRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class PackageRepository implements PackageRepositoryInterface
{
    /**
     * Tüm paketleri getir
     * Performans: Eager loading desteği
     *
     * @param array $relations
     * @return Collection
     */
    public function getAll(array $relations = []): Collection
    {
        $query = Package::query();

        if (!empty($relations)) {
            $query->with($relations);
        }

        return $query->sorted()->get();
    }

    /**
     * Sadece aktif paketleri getir (sıralı)
     * Performans: Scope kullanımı + eager loading
     *
     * @param array $relations
     * @return Collection
     */
    public function getActivePackages(array $relations = []): Collection
    {
        $query = Package::active()->sorted();

        if (!empty($relations)) {
            $query->with($relations);
        }

        return $query->get();
    }

    /**
     * ID ile paket bul
     * Performans: Eager loading desteği
     *
     * @param int $id
     * @param array $relations
     * @return Package|null
     */
    public function findById(int $id, array $relations = []): ?Package
    {
        $query = Package::query();

        if (!empty($relations)) {
            $query->with($relations);
        }

        return $query->find($id);
    }

    /**
     * Paket oluştur
     *
     * @param array $data
     * @return Package
     */
    public function create(array $data): Package
    {
        return Package::create($data);
    }

    /**
     * Paket güncelle
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        $package = Package::find($id);

        if (!$package) {
            return false;
        }

        return $package->update($data);
    }

    /**
     * Paket sil
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $package = Package::find($id);

        if (!$package) {
            return false;
        }

        return $package->delete();
    }

    /**
     * Paketi aktif/pasif yap
     *
     * @param int $id
     * @param bool $isActive
     * @return bool
     */
    public function toggleActive(int $id, bool $isActive): bool
    {
        $package = Package::find($id);

        if (!$package) {
            return false;
        }

        return $package->update(['is_active' => $isActive]);
    }
}
