<?php

namespace Modules\Academic\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GeneralNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $message;
    protected $type;

    public function __construct($message, $type = 'general')
    {
        $this->message = $message;
        $this->type = $type;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => $this->type,
            'title' => 'School Notification',
            'message' => $this->message,
            'data' => [
                'type' => $this->type,
                'message' => $this->message,
            ],
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('School Notification')
            ->greeting("Hello {$notifiable->name}!")
            ->line($this->message)
            ->salutation('Best regards, School Management System');
    }
}
