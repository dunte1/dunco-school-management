<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PublicModule extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'icon',
        'short_description',
        'description',
        'features',
        'benefits',
        'hero_title',
        'hero_subtitle',
        'screenshot_path',
        'is_active',
        'sort_order',
        'seo_title',
        'seo_description',
    ];

    protected $casts = [
        'features' => 'array',
        'benefits' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
}
