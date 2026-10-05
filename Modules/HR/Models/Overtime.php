<?php

namespace Modules\HR\Models;

use Illuminate\Database\Eloquent\Model;

class Overtime extends Model
{
    protected $table = 'overtime';
    
    protected $fillable = [
        'staff_id', 'date', 'hours', 'rate_multiplier', 'reason', 'status', 
        'approved_by', 'approved_at', 'school_id'
    ];

    protected $casts = [
        'date' => 'date',
        'hours' => 'decimal:2',
        'rate_multiplier' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'approved_by');
    }

    public function school()
    {
        return $this->belongsTo(\App\Models\School::class);
    }
}
