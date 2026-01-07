<?php

namespace App\Services;

use App\Models\Package;
use App\Repositories\Interfaces\PackageRepositoryInterface;
use App\Services\Interfaces\PackageServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class PackageService implements PackageServiceInterface
{
    protected PackageRepositoryInterface $packageRepository;

    /**
     * Dependency Injection
     */
    public function __construct(PackageRepositoryInterface $packageRepository)
    {
        $this->packageRepository = $packageRepository;
    }

    /**
     * Tüm paketleri getir
     *
     * @return Collection
     */
    public function getAllPackages(): Collection
    {
        return $this->packageRepository->getAll();
    }

    /**
     * Sadece aktif paketleri getir (müşterilere gösterilecek)
     * Performans: Eager loading yok (basit liste)
     *
     * @return Collection
     */
    public function getActivePackages(): Collection
    {
        return $this->packageRepository->getActivePackages();
    }

    /**
     * ID ile paket getir
     *
     * @param int $id
     * @return Package|null
     */
    public function getPackageById(int $id): ?Package
    {
        return $this->packageRepository->findById($id);
    }

    /**
     * Paket oluştur (Admin)
     *
     * @param array $data
     * @return Package
     */
    public function createPackage(array $data): Package
    {
        // Varsayılan değerler
        $data['is_active'] = $data['is_active'] ?? true;
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $this->packageRepository->create($data);
    }

    /**
     * Paket güncelle (Admin)
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updatePackage(int $id, array $data): bool
    {
        return $this->packageRepository->update($id, $data);
    }

    /**
     * Paket sil (Admin)
     *
     * @param int $id
     * @return bool
     */
    public function deletePackage(int $id): bool
    {
        // İş kuralı: Sepette veya siparişlerde kullanılıyorsa silinemez
        $package = $this->packageRepository->findById($id, ['cartItems', 'orderItems']);

        if (!$package) {
            return false;
        }

        // Sepette veya siparişlerde varsa sil
        if ($package->cartItems->count() > 0 || $package->orderItems->count() > 0) {
            // Silmek yerine pasif yap
            return $this->packageRepository->toggleActive($id, false);
        }

        return $this->packageRepository->delete($id);
    }

    /**
     * Paketi aktif/pasif yap (Admin)
     *
     * @param int $id
     * @param bool $isActive
     * @return bool
     */
    public function togglePackageStatus(int $id, bool $isActive): bool
    {
        return $this->packageRepository->toggleActive($id, $isActive);
    }

    /**
     * Paket satış istatistikleri
     * Performans: withCount kullanılabilir
     *
     * @param int $packageId
     * @return array
     */
    public function getPackageStats(int $packageId): array
    {
        $package = $this->packageRepository->findById($packageId, ['orderItems']);

        if (!$package) {
            return [];
        }

        $totalSales = $package->orderItems->sum('quantity');
        $totalRevenue = $package->orderItems->sum('subtotal');

        return [
            'package_id' => $packageId,
            'package_name' => $package->name,
            'total_sales' => $totalSales,
            'total_revenue' => $totalRevenue,
            'is_active' => $package->is_active,
        ];
    }
}
