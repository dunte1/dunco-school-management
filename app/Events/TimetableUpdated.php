<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Modules\Timetable\Models\Timetable;

class TimetableUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $timetable;
    public $action;

    public function __construct(Timetable $timetable, $action = 'updated')
    {
        $this->timetable = $timetable;
        $this->action = $action;
    }

    public function broadcastOn()
    {
        return [
            new PrivateChannel('class.' . $this->timetable->class_id),
            new PrivateChannel('teacher.' . $this->timetable->teacher_id),
        ];
    }

    public function broadcastWith()
    {
        return [
            'type' => 'timetable_updated',
            'timetable_id' => $this->timetable->id,
            'class_id' => $this->timetable->class_id,
            'subject_id' => $this->timetable->subject_id,
            'subject_name' => $this->timetable->subject->name ?? 'Unknown',
            'teacher_id' => $this->timetable->teacher_id,
            'teacher_name' => $this->timetable->teacher->user->name ?? 'Unknown',
            'day' => $this->timetable->day,
            'start_time' => $this->timetable->start_time,
            'end_time' => $this->timetable->end_time,
            'room' => $this->timetable->room,
            'action' => $this->action,
            'timestamp' => now()->toISOString(),
        ];
    }
}
