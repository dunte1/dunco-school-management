<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceAlertRule extends Model
{
    protected $fillable = [
        'school_id', 'name', 'type', 'threshold', 'window_days', 'channel', 'template_name', 'is_active', 'last_run_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_run_at' => 'datetime',
        'threshold' => 'integer',
        'window_days' => 'integer',
        'school_id' => 'integer',
    ];
}



