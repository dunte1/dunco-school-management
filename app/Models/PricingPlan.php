<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricingPlan extends Model
{
    protected $fillable = ['name', 'slug', 'price', 'currency', 'billing_period', 'description', 'is_featured', 'is_active', 'sort_order'];

    protected $casts = [
        'price' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function features() { return $this->hasMany(PricingFeature::class, 'pricing_plan_id')->orderBy('sort_order'); }
    public function scopeActive($query) { return $query->where('is_active', true); }
    public function scopeFeatured($query) { return $query->where('is_featured', true); }
    public function scopeOrdered($query) { return $query->orderBy('sort_order')->orderBy('price'); }

    public function getFormattedPriceAttribute()
    {
        return $this->currency . ' ' . number_format($this->price, 2);
    }
}
