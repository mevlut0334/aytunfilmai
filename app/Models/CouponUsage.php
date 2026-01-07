<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponUsage extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'coupon_id',
        'user_id',
        'order_id',
        'discount_amount',
        'used_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'discount_amount' => 'decimal:2',
            'used_at' => 'datetime',
        ];
    }

    /**
     * İlişki: Kullanım kaydının kuponu (Belongs To)
     * Performans için eager loading: CouponUsage::with('coupon')
     */
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    /**
     * İlişki: Kullanım kaydının kullanıcısı (Belongs To)
     * Performans için eager loading: CouponUsage::with('user')
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * İlişki: Kullanım kaydının siparişi (Belongs To)
     * Performans için eager loading: CouponUsage::with('order')
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Scope: Belirli kuponun kullanımları
     * Kullanım: CouponUsage::forCoupon($couponId)->get()
     */
    public function scopeForCoupon($query, $couponId)
    {
        return $query->where('coupon_id', $couponId);
    }

    /**
     * Scope: Belirli kullanıcının kupon kullanımları
     * Kullanım: CouponUsage::forUser($userId)->get()
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope: Belirli siparişin kupon kullanımı
     * Kullanım: CouponUsage::forOrder($orderId)->first()
     */
    public function scopeForOrder($query, $orderId)
    {
        return $query->where('order_id', $orderId);
    }
}
