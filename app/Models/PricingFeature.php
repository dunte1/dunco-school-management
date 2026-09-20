<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricingFeature extends Model
{
    protected $fillable = ['pricing_plan_id', 'feature_text', 'is_included', 'sort_order'];

    protected $casts = [
        'is_included' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function plan() { return $this->belongsTo(PricingPlan::class, 'pricing_plan_id'); }
}
