<?php

namespace App\Models\Modules\Academic\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceAuditLog extends Model
{
    protected $fillable = [
        'attendance_record_id', 'user_id', 'action', 'before', 'after'
    ];

    protected $casts = [
        'before' => 'array',
        'after' => 'array',
    ];

    public function attendanceRecord()
    {
        return $this->belongsTo(AttendanceRecord::class, 'attendance_record_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
