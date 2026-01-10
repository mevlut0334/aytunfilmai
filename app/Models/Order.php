<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'total_amount',
        'discount_amount',
        'final_amount',
        'coupon_id',
        'status',
        'callback_token', // EKLENDI
        'payment_date',
        'transaction_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'final_amount' => 'decimal:2',
            'payment_date' => 'datetime',
        ];
    }

    /**
     * İlişki: Siparişin sahibi kullanıcı (Belongs To)
     * Performans için eager loading: Order::with('user')
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * İlişki: Siparişin kuponu (Belongs To)
     * Performans için eager loading: Order::with('coupon')
     */
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    /**
     * İlişki: Siparişin kalemleri (One-to-Many)
     * Performans için eager loading: Order::with('orderItems')
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * İlişki: Siparişin token işlemleri (One-to-Many)
     * Performans için eager loading: Order::with('tokenTransactions')
     */
    public function tokenTransactions()
    {
        return $this->hasMany(TokenTransaction::class);
    }

    /**
     * İlişki: Siparişin kupon kullanım kaydı (One-to-One)
     * Performans için eager loading: Order::with('couponUsage')
     */
    public function couponUsage()
    {
        return $this->hasOne(CouponUsage::class);
    }

    /**
     * Scope: Belirli kullanıcının siparişleri
     * Kullanım: Order::forUser($userId)->get()
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope: Tamamlanmış siparişler
     * Kullanım: Order::completed()->get()
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope: Bekleyen siparişler
     * Kullanım: Order::pending()->get()
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Başarısız siparişler
     * Kullanım: Order::failed()->get()
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Sipariş tamamlandı mı?
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Sipariş beklemede mi?
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Sipariş başarısız mı?
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Toplam token miktarı
     * Performans: withSum kullanılabilir
     */
    public function getTotalTokensAttribute(): int
    {
        return $this->orderItems->sum(function($item) {
            return $item->quantity * $item->package->token_amount;
        });
    }
}
