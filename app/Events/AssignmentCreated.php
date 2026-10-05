<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Modules\Academic\Models\SubjectResource;

class AssignmentCreated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $assignment;
    public $action;

    public function __construct(SubjectResource $assignment, $action = 'created')
    {
        $this->assignment = $assignment;
        $this->action = $action;
    }

    public function broadcastOn()
    {
        return [
            new PrivateChannel('class.' . $this->assignment->class_id),
            new PrivateChannel('subject.' . $this->assignment->subject_id),
        ];
    }

    public function broadcastWith()
    {
        return [
            'type' => 'assignment_created',
            'assignment_id' => $this->assignment->id,
            'title' => $this->assignment->title,
            'description' => $this->assignment->description,
            'class_id' => $this->assignment->class_id,
            'subject_id' => $this->assignment->subject_id,
            'subject_name' => $this->assignment->subject->name ?? 'Unknown',
            'due_date' => $this->assignment->due_date,
            'total_marks' => $this->assignment->total_marks,
            'teacher_name' => $this->assignment->teacher->user->name ?? 'Unknown',
            'action' => $this->action,
            'timestamp' => now()->toISOString(),
        ];
    }
}
