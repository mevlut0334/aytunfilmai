<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'package_id',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'unit_price' => 'decimal:2',
            'subtotal' => 'decimal:2',
        ];
    }

    /**
     * İlişki: Sipariş kaleminin sipariş (Belongs To)
     * Performans için eager loading: OrderItem::with('order')
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * İlişki: Sipariş kaleminin paketi (Belongs To)
     * Performans için eager loading: OrderItem::with('package')
     */
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Scope: Belirli siparişin kalemleri
     * Kullanım: OrderItem::forOrder($orderId)->get()
     */
    public function scopeForOrder($query, $orderId)
    {
        return $query->where('order_id', $orderId);
    }

    /**
     * Toplam token miktarı
     */
    public function getTotalTokensAttribute(): int
    {
        return $this->quantity * $this->package->token_amount;
    }
}
