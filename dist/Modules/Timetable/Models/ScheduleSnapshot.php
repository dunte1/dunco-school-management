<?php

namespace Modules\Timetable\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduleSnapshot extends Model
{
    protected $fillable = [
        'schedule_id',
        'timetable_id',
        'data',
        'action',
        'user_id',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(ClassSchedule::class, 'schedule_id');
    }

    public function timetable(): BelongsTo
    {
        return $this->belongsTo(Timetable::class, 'timetable_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
} 