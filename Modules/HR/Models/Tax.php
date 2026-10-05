<?php

namespace Modules\HR\Models;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    protected $table = 'tax_rates';
    
    protected $fillable = [
        'name',
        'description',
        'rate',
        'min_amount',
        'max_amount',
        'fixed_amount',
        'is_active',
        'school_id'
    ];

    protected $casts = [
        'rate' => 'decimal:2',
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'fixed_amount' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    /**
     * Scope to get only active taxes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the tax amount for a given salary
     */
    public function calculateTax($amount)
    {
        if (!$this->is_active) {
            return 0;
        }

        // Check if amount is within the tax bracket
        if ($this->min_amount && $amount < $this->min_amount) {
            return 0;
        }

        if ($this->max_amount && $amount > $this->max_amount) {
            return 0;
        }

        return ($amount * $this->rate) / 100;
    }

    /**
     * Get formatted rate
     */
    public function getFormattedRateAttribute()
    {
        return $this->rate . '%';
    }

    /**
     * Get formatted amount range
     */
    public function getFormattedRangeAttribute()
    {
        if ($this->min_amount && $this->max_amount) {
            return number_format($this->min_amount, 2) . ' - ' . number_format($this->max_amount, 2);
        } elseif ($this->min_amount) {
            return 'Above ' . number_format($this->min_amount, 2);
        } elseif ($this->max_amount) {
            return 'Below ' . number_format($this->max_amount, 2);
        }
        
        return 'All amounts';
    }
}
