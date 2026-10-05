<?php

namespace Modules\ChatBot\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ChatBotAlert extends Notification
{
    use Queueable;

    protected $message;
    protected $type;
    protected $data;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($message, $type = 'info', $data = [])
    {
        $this->message = $message;
        $this->type = $type;
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'message' => $this->message,
            'type' => $this->type,
            'data' => $this->data,
            'timestamp' => now(),
        ];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('ChatBot Alert:')
                    ->line($this->message)
                    ->action('View Dashboard', url('/chatbot/admin'))
                    ->line('Thank you for using our application!');
    }
}