<?php

namespace Modules\Academic\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\Academic\Models\OnlineClass;

class OnlineClassReminder extends Notification implements ShouldQueue
{
    use Queueable;

    protected $onlineClass;

    public function __construct(OnlineClass $onlineClass)
    {
        $this->onlineClass = $onlineClass;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toDatabase($notifiable)
    {
        $minutesUntilStart = now()->diffInMinutes($this->onlineClass->start_time, false);
        
        return [
            'type' => 'online_class_reminder',
            'title' => 'Online Class Reminder',
            'message' => "Your online class '{$this->onlineClass->title}' starts in {$minutesUntilStart} minutes!",
            'data' => [
                'class_id' => $this->onlineClass->id,
                'title' => $this->onlineClass->title,
                'start_time' => $this->onlineClass->start_time,
                'meeting_link' => $this->onlineClass->meeting_link,
                'minutes_until_start' => $minutesUntilStart,
            ],
            'action_url' => $this->onlineClass->meeting_link,
        ];
    }

    public function toMail($notifiable)
    {
        $minutesUntilStart = now()->diffInMinutes($this->onlineClass->start_time, false);
        
        return (new MailMessage)
            ->subject("Reminder: Online Class Starting Soon - {$this->onlineClass->title}")
            ->greeting("Hello {$notifiable->name}!")
            ->line("This is a reminder that your online class is starting soon!")
            ->line("**Class Details:**")
            ->line("• **Title:** {$this->onlineClass->title}")
            ->line("• **Starts in:** {$minutesUntilStart} minutes")
            ->line("• **Start Time:** {$this->onlineClass->start_time->format('M d, Y \a\t g:i A')}")
            ->line("• **Meeting Link:** {$this->onlineClass->meeting_link}")
            ->when($this->onlineClass->meeting_password, function($mail) {
                return $mail->line("• **Meeting Password:** {$this->onlineClass->meeting_password}");
            })
            ->action('Join Class Now', $this->onlineClass->meeting_link)
            ->line('Please join the class on time. The meeting link is ready for you!')
            ->salutation('Best regards, School Management System');
    }
}
