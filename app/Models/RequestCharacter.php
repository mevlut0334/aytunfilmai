<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RequestCharacter extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'request_id',
        'name',
    ];

    /**
     * İlişkiler
     */
    public function request(): BelongsTo
    {
        return $this->belongsTo(Request::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(RequestCharacterImage::class, 'character_id');
    }

    /**
     * Scope'lar
     */
    public function scopeForRequest($query, int $requestId)
    {
        return $query->where('request_id', $requestId);
    }

    /**
     * Helper Metodlar
     */
    public function getImageCountAttribute(): int
    {
        return $this->images->count();
    }
}
