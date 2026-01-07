<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'package_id',
        'quantity',
        'price',
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
            'price' => 'decimal:2',
        ];
    }

    /**
     * İlişki: Sepet öğesinin sahibi kullanıcı (Belongs To)
     * Performans için eager loading: CartItem::with('user')
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * İlişki: Sepet öğesinin paketi (Belongs To)
     * Performans için eager loading: CartItem::with('package')
     */
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Scope: Belirli kullanıcının sepeti
     * Kullanım: CartItem::forUser($userId)->get()
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Ara toplam hesapla
     */
    public function getSubtotalAttribute(): float
    {
        return $this->quantity * $this->price;
    }

    /**
     * Toplam token miktarı
     */
    public function getTotalTokensAttribute(): int
    {
        return $this->quantity * $this->package->token_amount;
    }
}
