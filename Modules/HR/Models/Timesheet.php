<?php

namespace Modules\HR\Models;

use Illuminate\Database\Eloquent\Model;

class Timesheet extends Model
{
    protected $fillable = [
        'staff_id', 'date', 'start_time', 'end_time', 'hours_worked', 'description', 
        'status', 'approved_by', 'approved_at', 'school_id'
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'hours_worked' => 'decimal:2',
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
