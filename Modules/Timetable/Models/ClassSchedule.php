<?php

namespace Modules\Timetable\Models;

use Illuminate\Database\Eloquent\Model;

class ClassSchedule extends Model
{
    protected $fillable = [
        'academic_class_id',
        'teacher_id',
        'room_id',
        'timetable_id',
        'day_of_week',
        'start_time',
        'end_time',
        'status',
        'approved_by',
        'approved_at',
        'rejected_by',
        'rejected_at',
        'rejection_reason',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public function academicClass()
    {
        return $this->belongsTo(\Modules\Academic\Models\AcademicClass::class, 'academic_class_id');
    }

    public function teacher()
    {
        return $this->belongsTo(\App\Models\Teacher::class, 'teacher_id');
    }

    public function room()
    {
        return $this->belongsTo(\Modules\Timetable\Models\Room::class, 'room_id');
    }

    public function timetable()
    {
        return $this->belongsTo(\Modules\Timetable\Models\Timetable::class, 'timetable_id');
    }

    public function subject()
    {
        return $this->belongsTo(\Modules\Academic\Models\Subject::class, 'subject_id');
    }
} 