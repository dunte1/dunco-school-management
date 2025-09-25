<?php

namespace App\Models\Modules\Timetable\Models;

use Illuminate\Database\Eloquent\Model;

class RoomAvailability extends Model
{
    protected $fillable = [
        'room_id',
        'day_of_week',
        'start_time',
        'end_time',
    ];

    public function room()
    {
        return $this->belongsTo(\Modules\Timetable\Models\Room::class);
    }
}
