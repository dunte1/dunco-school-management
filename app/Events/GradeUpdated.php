<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Modules\Academic\Models\ExamResult;

class GradeUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $grade;
    public $student;
    public $action;

    public function __construct(ExamResult $grade, $action = 'updated')
    {
        $this->grade = $grade;
        $this->student = $grade->student;
        $this->action = $action;
    }

    public function broadcastOn()
    {
        return [
            new PrivateChannel('user.' . $this->student->user_id),
            new PrivateChannel('class.' . $this->grade->class_id),
            new PrivateChannel('teacher.' . $this->grade->teacher_id),
        ];
    }

    public function broadcastWith()
    {
        return [
            'type' => 'grade_updated',
            'grade_id' => $this->grade->id,
            'student_id' => $this->student->id,
            'student_name' => $this->student->user->name,
            'subject_id' => $this->grade->subject_id,
            'subject_name' => $this->grade->subject->name ?? 'Unknown',
            'score' => $this->grade->score,
            'grade' => $this->grade->grade,
            'class_id' => $this->grade->class_id,
            'action' => $this->action,
            'timestamp' => now()->toISOString(),
        ];
    }
}
