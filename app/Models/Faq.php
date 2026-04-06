<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $fillable = [
        'question',
        'answer',
        'translations',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active'    => 'boolean',
        'translations' => 'array',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public function getLocalizedQuestionAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->translations[$locale]['question'] ?? $this->question;
    }

    public function getLocalizedAnswerAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->translations[$locale]['answer'] ?? $this->answer;
    }
}
