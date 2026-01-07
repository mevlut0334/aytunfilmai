<?php

namespace App\Repositories;

use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Repositories\Interfaces\CouponRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CouponRepository implements CouponRepositoryInterface
{
    /**
     * Kupon kodu ile kupon bul
     * Performans: Scope kullanımı
     *
     * @param string $code
     * @return Coupon|null
     */
    public function findByCode(string $code): ?Coupon
    {
        return Coupon::byCode($code)->first();
    }

    /**
     * ID ile kupon bul
     *
     * @param int $id
     * @return Coupon|null
     */
    public function findById(int $id): ?Coupon
    {
        return Coupon::find($id);
    }

    /**
     * Tüm kuponları getir
     * Performans: Eager loading desteği
     *
     * @param array $relations
     * @return Collection
     */
    public function getAll(array $relations = []): Collection
    {
        $query = Coupon::query();

        if (!empty($relations)) {
            $query->with($relations);
        }

        return $query->latest()->get();
    }

    /**
     * Aktif kuponları getir
     * Performans: Scope kullanımı
     *
     * @return Collection
     */
    public function getActiveCoupons(): Collection
    {
        return Coupon::active()->latest()->get();
    }

    /**
     * Geçerli kuponları getir (aktif + tarih kontrolü)
     * Performans: Scope kullanımı
     *
     * @return Collection
     */
    public function getValidCoupons(): Collection
    {
        return Coupon::valid()->latest()->get();
    }

    /**
     * Kupon oluştur
     *
     * @param array $data
     * @return Coupon
     */
    public function create(array $data): Coupon
    {
        // Kupon kodunu uppercase yap
        if (isset($data['code'])) {
            $data['code'] = strtoupper($data['code']);
        }

        return Coupon::create($data);
    }

    /**
     * Kupon güncelle
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        $coupon = Coupon::find($id);

        if (!$coupon) {
            return false;
        }

        // Kupon kodunu uppercase yap
        if (isset($data['code'])) {
            $data['code'] = strtoupper($data['code']);
        }

        return $coupon->update($data);
    }

    /**
     * Kupon sil
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $coupon = Coupon::find($id);

        if (!$coupon) {
            return false;
        }

        return $coupon->delete();
    }

    /**
     * Kupon kullanım sayısını artır
     *
     * @param int $couponId
     * @return bool
     */
    public function incrementUsage(int $couponId): bool
    {
        $coupon = Coupon::find($couponId);

        if (!$coupon) {
            return false;
        }

        $coupon->incrementUsage();
        return true;
    }

    /**
     * Kupon kullanım kaydı oluştur
     *
     * @param array $data
     * @return void
     */
    public function createUsage(array $data): void
    {
        CouponUsage::create($data);
    }

    /**
     * Kullanıcının kupon kullanım geçmişi
     * Performans: Eager loading
     *
     * @param int $userId
     * @return Collection
     */
    public function getUserCouponUsages(int $userId): Collection
    {
        return CouponUsage::with(['coupon', 'order'])
            ->where('user_id', $userId)
            ->latest('used_at')
            ->get();
    }
}
