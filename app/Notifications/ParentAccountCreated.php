<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\User;

class ParentAccountCreated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;
    protected $password;

    public function __construct(User $user, $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    public function via($notifiable)
    {
        $channels = ['mail'];
        if (!empty($this->user->phone)) {
            $channels[] = 'vonage';
        }
        return $channels;
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Your Parent Portal Account')
            ->greeting('Hello ' . $this->user->name . ',')
            ->line('An account has been created for you on the school Parent Portal.')
            ->line('Login Email: ' . $this->user->email)
            ->line('Temporary Password: ' . $this->password)
            ->line('Please log in and change your password after your first login.')
            ->action('Parent Portal Login', url('/login'))
            ->line('If you have any questions, please contact the school administration.');
    }

    public function toVonage($notifiable)
    {
        return (new \Illuminate\Notifications\Messages\VonageMessage)
            ->content('Parent Portal Account: Email: ' . $this->user->email . ' Password: ' . $this->password . ' Please login and change your password.');
    }
} 