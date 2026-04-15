<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Paddle\Billable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, Billable, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'is_admin',
        'token_balance',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'token_balance' => 'decimal:2',
        ];
    }

    public function consent()
    {
        return $this->hasOne(UserConsent::class);
    }

    public function requests()
    {
        return $this->hasMany(Request::class);
    }

    public function tokenTransactions()
    {
        return $this->hasMany(TokenTransaction::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function scopeAdmins($query)
    {
        return $query->where('is_admin', true);
    }

    public function scopeNormalUsers($query)
    {
        return $query->where('is_admin', false);
    }

    public function isAdmin(): bool
    {
        return $this->is_admin;
    }

    public function hasEnoughTokens(float $amount): bool
    {
        return $this->token_balance >= $amount;
    }
}
