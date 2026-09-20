<?php

namespace Modules\Attendance\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceBiometricLog extends Model
{
    protected $table = 'attendance_biometric_logs';

    protected $fillable = [
        'student_id',
        'device_id',
        'scanned_at',
        'status',
        'raw_data',
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
        'raw_data' => 'array',
    ];

    public function student()
    {
        return $this->belongsTo(\Modules\Academic\Models\Student::class, 'student_id');
    }
}
