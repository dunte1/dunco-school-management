<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Modules\Academic\Models\AttendanceRecord;

class AttendanceMarked implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $attendance;
    public $student;
    public $action;

    public function __construct(AttendanceRecord $attendance, $action = 'marked')
    {
        $this->attendance = $attendance;
        $this->student = $attendance->student;
        $this->action = $action;
    }

    public function broadcastOn()
    {
        return [
            new PrivateChannel('user.' . $this->student->user_id),
            new PrivateChannel('class.' . $this->attendance->class_id),
            new PrivateChannel('teacher.' . $this->attendance->teacher_id),
        ];
    }

    public function broadcastWith()
    {
        return [
            'type' => 'attendance_marked',
            'attendance_id' => $this->attendance->id,
            'student_id' => $this->student->id,
            'student_name' => $this->student->user->name,
            'class_id' => $this->attendance->class_id,
            'status' => $this->attendance->status,
            'date' => $this->attendance->date,
            'action' => $this->action,
            'timestamp' => now()->toISOString(),
        ];
    }
}
