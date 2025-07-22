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
    ];

    public function academicClass()
    {
        return $this->belongsTo(\Modules\Academic\Models\AcademicClass::class, 'academic_class_id');
    }

    public function teacher()
    {
        return $this->belongsTo(\Modules\HR\Models\Staff::class, 'teacher_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function timetable()
    {
        return $this->belongsTo(Timetable::class, 'timetable_id');
    }
} 