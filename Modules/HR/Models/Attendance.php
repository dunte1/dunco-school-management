<?php

namespace Modules\HR\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'attendance';
    protected $fillable = [
        'staff_id', 'date', 'clock_in', 'clock_out', 'status', 'remarks'
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
} 