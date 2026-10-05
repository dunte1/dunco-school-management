<?php

namespace Modules\HR\Models;

use Illuminate\Database\Eloquent\Model;

class Deduction extends Model
{
    protected $fillable = [
        'name', 'description', 'type', 'amount', 'calculation_type', 'is_active', 'school_id'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function school()
    {
        return $this->belongsTo(\App\Models\School::class);
    }
}
