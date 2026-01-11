<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScrollingImage extends Model
{
    protected $fillable = [
        'title',
        'image',
        'link',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Scope: Sadece aktif görseller
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Sıralı görseller
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
