<?php

namespace App\Repositories\Interfaces;

use App\Models\Coupon;
use Illuminate\Database\Eloquent\Collection;

interface CouponRepositoryInterface
{
    /**
     * Kupon kodu ile kupon bul
     *
     * @param string $code
     * @return Coupon|null
     */
    public function findByCode(string $code): ?Coupon;

    /**
     * ID ile kupon bul
     *
     * @param int $id
     * @return Coupon|null
     */
    public function findById(int $id): ?Coupon;

    /**
     * Tüm kuponları getir
     *
     * @param array $relations Eager loading için ilişkiler
     * @return Collection
     */
    public function getAll(array $relations = []): Collection;

    /**
     * Aktif kuponları getir
     *
     * @return Collection
     */
    public function getActiveCoupons(): Collection;

    /**
     * Geçerli kuponları getir (aktif + tarih kontrolü)
     *
     * @return Collection
     */
    public function getValidCoupons(): Collection;

    /**
     * Kupon oluştur
     *
     * @param array $data
     * @return Coupon
     */
    public function create(array $data): Coupon;

    /**
     * Kupon güncelle
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool;

    /**
     * Kupon sil
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * Kupon kullanım sayısını artır
     *
     * @param int $couponId
     * @return bool
     */
    public function incrementUsage(int $couponId): bool;

    /**
     * Kupon kullanım kaydı oluştur
     *
     * @param array $data
     * @return void
     */
    public function createUsage(array $data): void;

    /**
     * Kullanıcının kupon kullanım geçmişi
     *
     * @param int $userId
     * @return Collection
     */
    public function getUserCouponUsages(int $userId): Collection;
}
