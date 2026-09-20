<?php

namespace Modules\Attendance\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceAcknowledgment extends Model
{
    protected $table = 'attendance_acknowledgments';

    protected $fillable = [
        'attendance_record_id',
        'parent_id',
        'acknowledged_at',
        'channel',
    ];

    protected $casts = [
        'acknowledged_at' => 'datetime',
    ];

    public function attendanceRecord()
    {
        return $this->belongsTo(\Modules\Academic\Models\AttendanceRecord::class, 'attendance_record_id');
    }

    public function parent()
    {
        return $this->belongsTo(\App\Models\User::class, 'parent_id');
    }
}
