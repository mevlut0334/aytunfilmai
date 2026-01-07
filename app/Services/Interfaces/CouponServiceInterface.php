<?php

namespace App\Services\Interfaces;

use App\Models\Coupon;
use Illuminate\Database\Eloquent\Collection;

interface CouponServiceInterface
{
    /**
     * Kupon kodu ile kupon kontrol et ve getir
     *
     * @param string $code
     * @param float $amount
     * @return array (valid, coupon, discount, message)
     */
    public function validateCoupon(string $code, float $amount): array;

    /**
     * Tüm kuponları getir (Admin)
     *
     * @return Collection
     */
    public function getAllCoupons(): Collection;

    /**
     * Aktif kuponları getir (Admin)
     *
     * @return Collection
     */
    public function getActiveCoupons(): Collection;

    /**
     * ID ile kupon getir
     *
     * @param int $id
     * @return Coupon|null
     */
    public function getCouponById(int $id): ?Coupon;

    /**
     * Kupon oluştur (Admin)
     *
     * @param array $data
     * @return Coupon
     */
    public function createCoupon(array $data): Coupon;

    /**
     * Kupon güncelle (Admin)
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateCoupon(int $id, array $data): bool;

    /**
     * Kupon sil (Admin)
     *
     * @param int $id
     * @return bool
     */
    public function deleteCoupon(int $id): bool;

    /**
     * Kupon istatistikleri (Admin)
     *
     * @param int $couponId
     * @return array
     */
    public function getCouponStats(int $couponId): array;

    /**
     * Kullanıcının kupon kullanım geçmişi
     *
     * @param int $userId
     * @return Collection
     */
    public function getUserCouponHistory(int $userId): Collection;
}
