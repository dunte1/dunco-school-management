<?php

namespace Modules\Timetable\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'name',
        'capacity',
        'location',
        'type',      // Lecture Room, Lab, Hall, Studio, etc.
        'equipment', // JSON or comma-separated list: Projector, Computers, etc.
        'availability_time', // JSON or string, nullable, for maintenance/unavailable times
    ];

    public function schedules()
    {
        return $this->hasMany(ClassSchedule::class);
    }
} 