<?php

namespace Modules\Attendance\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceQrLog extends Model
{
    protected $table = 'attendance_qr_logs';

    protected $fillable = [
        'student_id',
        'session_id',
        'scanned_at',
        'status',
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(\Modules\Academic\Models\Student::class, 'student_id');
    }

    public function session()
    {
        return $this->belongsTo(AttendanceSession::class, 'session_id');
    }
}
