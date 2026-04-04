<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Request extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'status',
        'video_url',
        'video_format',
        'error_message',
        'processed_by',
        'processed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'processed_at' => 'datetime',
    ];

    /**
     * İlişkiler
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function characters(): HasMany
    {
        return $this->hasMany(RequestCharacter::class);
    }

    /**
     * Talebi işleyen admin
     * Performans için eager loading: Request::with('processedBy')
     */
    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Scope'lar
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Belirli bir admin tarafından işlenenleri getir
     */
    public function scopeProcessedBy($query, int $adminId)
    {
        return $query->where('processed_by', $adminId);
    }

    /**
     * Helper Metodlar
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isProcessing(): bool
    {
        return $this->status === 'processing';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Admin tarafından işlendi mi?
     */
    public function isProcessedByAdmin(): bool
    {
        return $this->processed_by !== null;
    }

    /**
     * İşleyen admin'in adı (varsa)
     */
    public function getProcessedByNameAttribute(): ?string
    {
        return $this->processedBy?->name;
    }

    public function getTotalImagesAttribute(): int
    {
        return $this->characters->sum(function ($character) {
            return $character->images->count();
        });
    }
}
