<?php

namespace Modules\Attendance\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceFaceLog extends Model
{
    protected $table = 'attendance_face_logs';

    protected $fillable = [
        'student_id',
        'image_hash',
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
}
