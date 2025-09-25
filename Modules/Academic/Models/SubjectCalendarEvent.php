<?php

namespace Modules\Academic\Models;

use Illuminate\Database\Eloquent\Model;

class SubjectCalendarEvent extends Model
{
    protected $table = 'subject_calendar_events';
    protected $fillable = ['subject_id', 'event_id', 'provider', 'event_url', 'start_time', 'end_time'];

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
} 