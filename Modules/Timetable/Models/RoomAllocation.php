<?php

namespace Modules\Timetable\Models;

use Illuminate\Database\Eloquent\Model;

class RoomAllocation extends Model
{
    protected $fillable = [
        'room_id',
        'class_schedule_id',
        'allocation_date',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function classSchedule()
    {
        return $this->belongsTo(ClassSchedule::class);
    }
} 