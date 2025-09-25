<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        // Add fillable fields as needed
    ];

    /**
     * Get the user associated with this teacher.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the class schedules for this teacher.
     */
    public function classSchedules()
    {
        return $this->hasMany(\Modules\Timetable\Models\ClassSchedule::class, 'teacher_id');
    }

    /**
     * Get the teacher availabilities for this teacher.
     */
    public function availabilities()
    {
        return $this->hasMany(\Modules\Timetable\Models\TeacherAvailability::class, 'teacher_id');
    }
} 