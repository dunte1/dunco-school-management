<?php

namespace Modules\Finance\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class FinanceInAppNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Finance Notification - Dunco School')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line($this->message)
            ->salutation('Regards, Dunco School Management');
    }

    public function toArray($notifiable)
    {
        return [
            'message' => $this->message,
            'title' => 'Finance Notification',
            'type' => 'finance',
        ];
    }
}