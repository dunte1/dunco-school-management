<?php

namespace Modules\Portal\app\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\VonageMessage;

class GeneralAnnouncement extends Notification implements ShouldQueue
{
    use Queueable;

    public $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function via($notifiable)
    {
        $channels = ['database', 'mail'];
        if (!empty($notifiable->phone)) {
            $channels[] = 'vonage';
        }
        return $channels;
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('School Announcement')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line($this->message)
            ->line('Thank you for being part of our school community!');
    }

    public function toVonage($notifiable)
    {
        return (new VonageMessage)
            ->content('School Announcement: ' . $this->message);
    }

    public function toArray($notifiable)
    {
        return [
            'message' => $this->message,
        ];
    }
} 