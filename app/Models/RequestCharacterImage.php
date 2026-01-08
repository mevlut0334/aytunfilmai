<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class RequestCharacterImage extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'character_id',
        'image_path',
        'order',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'order' => 'integer',
    ];

    /**
     * İlişkiler
     */
    public function character(): BelongsTo
    {
        return $this->belongsTo(RequestCharacter::class, 'character_id');
    }

    /**
     * Scope'lar
     */
    public function scopeForCharacter($query, int $characterId)
    {
        return $query->where('character_id', $characterId);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    /**
     * Helper Metodlar
     */
    public function getImageUrlAttribute(): string
    {
        return Storage::url($this->image_path);
    }

    public function getFullPathAttribute(): string
    {
        return Storage::path($this->image_path);
    }
}
