<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserConsent extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'terms_accepted',
        'copyright_accepted',
        'kvkk_accepted',
        'personal_data_accepted',
        'ip_address',
        'user_agent',
        'accepted_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'terms_accepted' => 'boolean',
            'copyright_accepted' => 'boolean',
            'kvkk_accepted' => 'boolean',
            'personal_data_accepted' => 'boolean',
            'accepted_at' => 'datetime',
        ];
    }

    /**
     * İlişki: Onayın ait olduğu kullanıcı (Belongs To)
     * Performans için eager loading: UserConsent::with('user')
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Tüm onaylar verilmiş mi?
     */
    public function allConsentsGiven(): bool
    {
        return $this->terms_accepted
            && $this->copyright_accepted
            && $this->kvkk_accepted
            && $this->personal_data_accepted;
    }
}
