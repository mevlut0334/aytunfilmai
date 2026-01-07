<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TokenTransaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'amount',
        'type',
        'description',
        'order_id',
        'balance_after',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'balance_after' => 'decimal:2',
        ];
    }

    /**
     * İlişki: İşlemin sahibi kullanıcı (Belongs To)
     * Performans için eager loading: TokenTransaction::with('user')
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * İlişki: İşlemin bağlı olduğu sipariş (Belongs To)
     * Performans için eager loading: TokenTransaction::with('order')
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Scope: Sadece token ekleme işlemleri
     * Kullanım: TokenTransaction::credits()->get()
     */
    public function scopeCredits($query)
    {
        return $query->where('type', 'credit');
    }

    /**
     * Scope: Sadece token düşme işlemleri
     * Kullanım: TokenTransaction::debits()->get()
     */
    public function scopeDebits($query)
    {
        return $query->where('type', 'debit');
    }

    /**
     * Scope: Belirli kullanıcının işlemleri
     * Kullanım: TokenTransaction::forUser($userId)->get()
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Token ekleme işlemi mi?
     */
    public function isCredit(): bool
    {
        return $this->type === 'credit';
    }

    /**
     * Token düşme işlemi mi?
     */
    public function isDebit(): bool
    {
        return $this->type === 'debit';
    }
}
