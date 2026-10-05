<?php

namespace Modules\HR\Models;

use Illuminate\Database\Eloquent\Model;

class TaxRate extends Model
{
    protected $fillable = [
        'name', 'min_amount', 'max_amount', 'rate', 'fixed_amount', 'is_active', 'school_id'
    ];

    protected $casts = [
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'rate' => 'decimal:2',
        'fixed_amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function school()
    {
        return $this->belongsTo(\App\Models\School::class);
    }
}
