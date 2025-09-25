<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class ParentTimetableNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $student;
    protected $class;
    protected $schedule;
    protected $timetableUrl;
    protected $action;

    /**
     * Create a new notification instance.
     */
    public function __construct($student, $class, $schedule, $timetableUrl, $action = 'published')
    {
        $this->student = $student;
        $this->class = $class;
        $this->schedule = $schedule;
        $this->timetableUrl = $timetableUrl;
        $this->action = $action;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['mail', 'database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Timetable ' . ucfirst($this->action) . ' for ' . $this->student->name)
            ->greeting('Dear Parent,')
            ->line('The timetable for your child, ' . $this->student->name . ' (Class: ' . $this->class->name . '), has been ' . $this->action . '.')
            ->line('Affected Schedule: ' . $this->schedule)
            ->action('View Timetable', $this->timetableUrl)
            ->line('Thank you for staying informed!');
    }

    /**
     * Get the array representation of the notification (for database).
     */
    public function toArray($notifiable)
    {
        return [
            'student_id' => $this->student->id,
            'student_name' => $this->student->name,
            'class_id' => $this->class->id,
            'class_name' => $this->class->name,
            'schedule' => $this->schedule,
            'timetable_url' => $this->timetableUrl,
            'action' => $this->action,
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }
} 