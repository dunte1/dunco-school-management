<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class TeacherScheduleNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $schedule;
    public $action;

    public function __construct($schedule, $action)
    {
        $this->schedule = $schedule;
        $this->action = $action; // 'created', 'updated', 'removed'
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $msg = new MailMessage;
        $msg->subject('Schedule ' . ucfirst($this->action))
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('A schedule has been ' . $this->action . ' for you:')
            ->line('Class: ' . ($this->schedule->class->name ?? $this->schedule->class_id))
            ->line('Room: ' . ($this->schedule->room->name ?? $this->schedule->room_id))
            ->line('Day: ' . $this->schedule->day_of_week)
            ->line('Time: ' . $this->schedule->start_time . ' - ' . $this->schedule->end_time)
            ->action('View Timetable', url('/timetable/calendar'));
        return $msg;
    }

    public function toArray($notifiable)
    {
        return [
            'action' => $this->action,
            'class' => $this->schedule->class->name ?? $this->schedule->class_id,
            'room' => $this->schedule->room->name ?? $this->schedule->room_id,
            'day' => $this->schedule->day_of_week,
            'start_time' => $this->schedule->start_time,
            'end_time' => $this->schedule->end_time,
            'schedule_id' => $this->schedule->id,
        ];
    }
} 