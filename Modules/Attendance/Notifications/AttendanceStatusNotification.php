<?php

namespace Modules\Attendance\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AttendanceStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $student;
    protected $status;
    protected $date;

    public function __construct($student, $status, $date)
    {
        $this->student = $student;
        $this->status = $status;
        $this->date = $date;
    }

    public function via($notifiable)
    {
        return ['mail']; // Add 'nexmo' or 'sms' if SMS is set up
    }

    public function toMail($notifiable)
    {
        $statusText = ucfirst($this->status);
        return (new MailMessage)
            ->subject('Attendance Alert: ' . $this->student->first_name . ' ' . $this->student->last_name)
            ->greeting('Dear Parent,')
            ->line('This is to inform you that your child, ' . $this->student->first_name . ' ' . $this->student->last_name . ', was marked as ' . $statusText . ' on ' . $this->date . '.')
            ->line('If you have any questions, please contact the school administration.')
            ->salutation('Regards, School Management');
    }

    // Optionally add toArray or toSms for other channels
} 