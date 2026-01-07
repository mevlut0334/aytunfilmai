<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Coupon extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'type',
        'discount_value',
        'min_amount',
        'max_usage',
        'usage_count',
        'is_active',
        'starts_at',
        'expires_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'discount_value' => 'decimal:2',
            'min_amount' => 'decimal:2',
            'max_usage' => 'integer',
            'usage_count' => 'integer',
            'is_active' => 'boolean',
            'starts_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    /**
     * İlişki: Kuponun siparişleri (One-to-Many)
     * Performans için eager loading: Coupon::with('orders')
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * İlişki: Kuponun kullanım kayıtları (One-to-Many)
     * Performans için eager loading: Coupon::with('usages')
     */
    public function usages()
    {
        return $this->hasMany(CouponUsage::class);
    }

    /**
     * Scope: Sadece aktif kuponlar
     * Kullanım: Coupon::active()->get()
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Geçerli kuponlar (aktif + tarih kontrolü)
     * Kullanım: Coupon::valid()->get()
     */
    public function scopeValid($query)
    {
        $now = Carbon::now();

        return $query->where('is_active', true)
            ->where(function($q) use ($now) {
                $q->whereNull('starts_at')
                  ->orWhere('starts_at', '<=', $now);
            })
            ->where(function($q) use ($now) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>=', $now);
            });
    }

    /**
     * Scope: Kupon koduna göre bul
     * Kullanım: Coupon::byCode('SUMMER2024')->first()
     */
    public function scopeByCode($query, $code)
    {
        return $query->where('code', strtoupper($code));
    }

    /**
     * Kupon aktif mi?
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Kupon geçerli mi? (aktif + tarih + kullanım sayısı)
     */
    public function isValid(): bool
    {
        $now = Carbon::now();

        // Aktif değilse
        if (!$this->is_active) {
            return false;
        }

        // Başlangıç tarihi kontrolü
        if ($this->starts_at && $this->starts_at->gt($now)) {
            return false;
        }

        // Bitiş tarihi kontrolü
        if ($this->expires_at && $this->expires_at->lt($now)) {
            return false;
        }

        // Maksimum kullanım kontrolü
        if ($this->max_usage && $this->usage_count >= $this->max_usage) {
            return false;
        }

        return true;
    }

    /**
     * İndirim tutarını hesapla
     */
    public function calculateDiscount(float $amount): float
    {
        if ($this->type === 'percentage') {
            $discount = ($amount * $this->discount_value) / 100;
        } else {
            $discount = $this->discount_value;
        }

        // İndirim tutarı toplam tutardan fazla olamaz
        return min($discount, $amount);
    }

    /**
     * Minimum tutar koşulu sağlanıyor mu?
     */
    public function meetsMinimumAmount(float $amount): bool
    {
        if (!$this->min_amount) {
            return true;
        }

        return $amount >= $this->min_amount;
    }

    /**
     * Kullanım sayısını artır
     */
    public function incrementUsage(): void
    {
        $this->increment('usage_count');
    }
}
