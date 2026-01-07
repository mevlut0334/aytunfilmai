<?php

namespace App\Services;

use App\Models\Coupon;
use App\Repositories\Interfaces\CouponRepositoryInterface;
use App\Services\Interfaces\CouponServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class CouponService implements CouponServiceInterface
{
    protected CouponRepositoryInterface $couponRepository;

    /**
     * Dependency Injection
     */
    public function __construct(CouponRepositoryInterface $couponRepository)
    {
        $this->couponRepository = $couponRepository;
    }

    /**
     * Kupon kodu ile kupon kontrol et ve getir
     *
     * @param string $code
     * @param float $amount
     * @return array
     */
    public function validateCoupon(string $code, float $amount): array
    {
        $coupon = $this->couponRepository->findByCode($code);

        // Kupon bulunamadı
        if (!$coupon) {
            return [
                'valid' => false,
                'coupon' => null,
                'discount' => 0,
                'message' => 'Kupon kodu geçersiz.',
            ];
        }

        // Kupon geçerli değil (aktif değil, tarih geçmiş, kullanım limiti)
        if (!$coupon->isValid()) {
            return [
                'valid' => false,
                'coupon' => $coupon,
                'discount' => 0,
                'message' => 'Bu kupon artık geçerli değil.',
            ];
        }


        // Minimum tutar kontrolü
        if (!$coupon->meetsMinimumAmount($amount)) {
            $minAmount = number_format((float) $coupon->min_amount, 2);
            return [
                'valid' => false,
                'coupon' => $coupon,
                'discount' => 0,
                'message' => "Bu kupon için minimum {$minAmount} TL alışveriş yapmalısınız.",
            ];
        }

        // Kupon geçerli, indirim hesapla
        $discount = $coupon->calculateDiscount($amount);

        return [
            'valid' => true,
            'coupon' => $coupon,
            'discount' => $discount,
            'message' => 'Kupon başarıyla uygulandı!',
        ];
    }

    /**
     * Tüm kuponları getir (Admin)
     * Performans: Eager loading ile kullanım kayıtları
     *
     * @return Collection
     */
    public function getAllCoupons(): Collection
    {
        return $this->couponRepository->getAll(['usages']);
    }

    /**
     * Aktif kuponları getir (Admin)
     *
     * @return Collection
     */
    public function getActiveCoupons(): Collection
    {
        return $this->couponRepository->getActiveCoupons();
    }

    /**
     * ID ile kupon getir
     *
     * @param int $id
     * @return Coupon|null
     */
    public function getCouponById(int $id): ?Coupon
    {
        return $this->couponRepository->findById($id);
    }

    /**
     * Kupon oluştur (Admin)
     *
     * @param array $data
     * @return Coupon
     * @throws \Exception
     */
    public function createCoupon(array $data): Coupon
    {
        // Validasyon
        if (empty($data['code'])) {
            throw new \Exception('Kupon kodu zorunludur.');
        }

        // Kupon kodu benzersizlik kontrolü
        $existing = $this->couponRepository->findByCode($data['code']);
        if ($existing) {
            throw new \Exception('Bu kupon kodu zaten kullanılmaktadır.');
        }

        // İndirim değeri kontrolü
        if ($data['type'] === 'percentage' && $data['discount_value'] > 100) {
            throw new \Exception('Yüzde indirimi 100\'den fazla olamaz.');
        }

        // Varsayılan değerler
        $data['usage_count'] = 0;
        $data['is_active'] = $data['is_active'] ?? true;

        return $this->couponRepository->create($data);
    }

    /**
     * Kupon güncelle (Admin)
     *
     * @param int $id
     * @param array $data
     * @return bool
     * @throws \Exception
     */
    public function updateCoupon(int $id, array $data): bool
    {
        $coupon = $this->couponRepository->findById($id);

        if (!$coupon) {
            throw new \Exception('Kupon bulunamadı.');
        }

        // Kupon kodu değiştiriliyorsa benzersizlik kontrolü
        if (isset($data['code']) && $data['code'] !== $coupon->code) {
            $existing = $this->couponRepository->findByCode($data['code']);
            if ($existing && $existing->id !== $id) {
                throw new \Exception('Bu kupon kodu zaten kullanılmaktadır.');
            }
        }

        // İndirim değeri kontrolü
        if (
            isset($data['type']) && $data['type'] === 'percentage' &&
            isset($data['discount_value']) && $data['discount_value'] > 100
        ) {
            throw new \Exception('Yüzde indirimi 100\'den fazla olamaz.');
        }

        return $this->couponRepository->update($id, $data);
    }

    /**
     * Kupon sil (Admin)
     *
     * @param int $id
     * @return bool
     * @throws \Exception
     */
    public function deleteCoupon(int $id): bool
    {
        $coupon = $this->couponRepository->findById($id);

        if (!$coupon) {
            throw new \Exception('Kupon bulunamadı.');
        }

        // İş kuralı: Kullanılmış kuponlar silinemez, sadece pasif yapılabilir
        if ($coupon->usage_count > 0) {
            // Silmek yerine pasif yap
            return $this->couponRepository->update($id, ['is_active' => false]);
        }

        return $this->couponRepository->delete($id);
    }

    /**
     * Kupon istatistikleri (Admin)
     * Performans: Eager loading ile kullanım kayıtları
     *
     * @param int $couponId
     * @return array
     */
    public function getCouponStats(int $couponId): array
    {
        $coupon = $this->couponRepository->findById($couponId);

        if (!$coupon) {
            return [];
        }

        $usages = $this->couponRepository->getUserCouponUsages($couponId);
        $totalDiscount = $usages->sum('discount_amount');

        return [
            'coupon_id' => $couponId,
            'coupon_code' => $coupon->code,
            'usage_count' => $coupon->usage_count,
            'max_usage' => $coupon->max_usage,
            'remaining_usage' => $coupon->max_usage ? ($coupon->max_usage - $coupon->usage_count) : null,
            'total_discount_given' => $totalDiscount,
            'is_active' => $coupon->is_active,
            'is_valid' => $coupon->isValid(),
        ];
    }

    /**
     * Kullanıcının kupon kullanım geçmişi
     * Performans: Repository'den eager loading ile
     *
     * @param int $userId
     * @return Collection
     */
    public function getUserCouponHistory(int $userId): Collection
    {
        return $this->couponRepository->getUserCouponUsages($userId);
    }
}
