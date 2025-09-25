<?php

namespace Modules\Attendance\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AttendanceSmsNotification extends Notification
{
    use Queueable;

    public $message;
    public function __construct($message)
    {
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['sms']; // Custom channel
    }

    public function toSms($notifiable)
    {
        return [
            'to' => $notifiable->phone,
            'message' => $this->message,
        ];
    }
} 