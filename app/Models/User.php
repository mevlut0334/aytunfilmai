<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'is_admin',
        'token_balance',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'token_balance' => 'decimal:2',
        ];
    }

    /**
     * İlişki: Kullanıcının onay kaydı (One-to-One)
     * Performans için eager loading kullanılabilir: User::with('consent')
     */
    public function consent()
    {
        return $this->hasOne(UserConsent::class);
    }

    // TODO: Request modeli oluşturulunca aktif edilecek
    // /**
    //  * İlişki: Kullanıcının talepleri (One-to-Many)
    //  * Performans için eager loading: User::with('requests')
    //  */
    // public function requests()
    // {
    //     return $this->hasMany(Request::class);
    // }

    /**
     * İlişki: Kullanıcının token işlemleri (One-to-Many)
     * Performans için eager loading: User::with('tokenTransactions')
     */
    public function tokenTransactions()
    {
        return $this->hasMany(TokenTransaction::class);
    }

    /**
     * İlişki: Kullanıcının siparişleri (One-to-Many)
     * Performans için eager loading: User::with('orders')
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * İlişki: Kullanıcının sepet öğeleri (One-to-Many)
     * Performans için eager loading: User::with('cartItems')
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Scope: Sadece admin kullanıcılar
     * Kullanım: User::admins()->get()
     */
    public function scopeAdmins($query)
    {
        return $query->where('is_admin', true);
    }

    /**
     * Scope: Sadece normal kullanıcılar
     * Kullanım: User::normalUsers()->get()
     */
    public function scopeNormalUsers($query)
    {
        return $query->where('is_admin', false);
    }

    /**
     * Admin kontrolü
     */
    public function isAdmin(): bool
    {
        return $this->is_admin;
    }

    /**
     * Token bakiyesi kontrolü
     */
    public function hasEnoughTokens(float $amount): bool
    {
        return $this->token_balance >= $amount;
    }
}
