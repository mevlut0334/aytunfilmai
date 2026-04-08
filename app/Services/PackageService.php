<?php

namespace App\Services;

use App\Models\Package;
use App\Repositories\Interfaces\PackageRepositoryInterface;
use App\Services\Interfaces\PackageServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class PackageService implements PackageServiceInterface
{
    protected PackageRepositoryInterface $packageRepository;
    protected PaddleService $paddleService;

    /**
     * Dependency Injection
     */
    public function __construct(
        PackageRepositoryInterface $packageRepository,
        PaddleService $paddleService
    ) {
        $this->packageRepository = $packageRepository;
        $this->paddleService     = $paddleService;
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
     * Paddle'dan canlı USD fiyatı çeker (5 dk cache'li)
     *
     * @return Collection
     */
    public function getActivePackages(): Collection
    {
        $packages = $this->packageRepository->getActivePackages();

        foreach ($packages as $package) {
            if ($package->paddle_price_id) {
                $paddlePrice = $this->paddleService->getPrice($package->paddle_price_id);

                $package->paddle_price    = $paddlePrice['amount']   ?? null;
                $package->paddle_currency = $paddlePrice['currency'] ?? 'USD';
            } else {
                $package->paddle_price    = null;
                $package->paddle_currency = 'USD';
            }
        }

        return $packages;
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
    $data['is_active']  = $data['is_active']  ?? true;
    $data['sort_order'] = $data['sort_order'] ?? 0;

    if (!empty($data['paddle_price_id'])) {
        $paddlePrice   = $this->paddleService->getPrice($data['paddle_price_id']);
        $data['price'] = $paddlePrice['amount'] ?? null;
    }

    return $this->packageRepository->create($data);
}

public function updatePackage(int $id, array $data): bool
{
    if (!empty($data['paddle_price_id'])) {
        $this->paddleService->clearPriceCache($data['paddle_price_id']);
        $paddlePrice   = $this->paddleService->getPrice($data['paddle_price_id']);
        $data['price'] = $paddlePrice['amount'] ?? null;
    }

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
        $package = $this->packageRepository->findById($id, ['cartItems', 'orderItems']);

        if (!$package) {
            return false;
        }

        if ($package->cartItems->count() > 0 || $package->orderItems->count() > 0) {
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

        $totalSales   = $package->orderItems->sum('quantity');
        $totalRevenue = $package->orderItems->sum('subtotal');

        return [
            'package_id'   => $packageId,
            'package_name' => $package->name,
            'total_sales'  => $totalSales,
            'total_revenue'=> $totalRevenue,
            'is_active'    => $package->is_active,
        ];
    }
}
