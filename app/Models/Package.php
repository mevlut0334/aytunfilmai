<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'token_amount',
        'price',
        'description',
        'is_active',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'price' => 'decimal:2',
            'token_amount' => 'integer',
        ];
    }

    /**
     * İlişki: Paketin sepet öğeleri (One-to-Many)
     * Performans için eager loading: Package::with('cartItems')
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * İlişki: Paketin sipariş kalemleri (One-to-Many)
     * Performans için eager loading: Package::with('orderItems')
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Scope: Sadece aktif paketler
     * Kullanım: Package::active()->get()
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Fiyata göre sıralı paketler
     * Kullanım: Package::sorted()->get()
     */
    public function scopeSorted($query)
    {
        return $query->orderBy('price', 'asc');
    }

    /**
     * Aktif mi kontrolü
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Toplam satış adedi
     * Performans: withCount kullanılabilir
     */
    public function getTotalSalesAttribute(): int
    {
        return $this->orderItems()->sum('quantity');
    }
}
