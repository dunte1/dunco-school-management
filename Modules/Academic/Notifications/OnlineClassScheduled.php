<?php

namespace Modules\Academic\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\Academic\Models\OnlineClass;

class OnlineClassScheduled extends Notification implements ShouldQueue
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
        return [
            'type' => 'online_class_scheduled',
            'title' => 'New Online Class Scheduled',
            'message' => "A new online class '{$this->onlineClass->title}' has been scheduled for {$this->onlineClass->start_time->format('M d, Y \a\t g:i A')}",
            'data' => [
                'class_id' => $this->onlineClass->id,
                'title' => $this->onlineClass->title,
                'start_time' => $this->onlineClass->start_time,
                'end_time' => $this->onlineClass->end_time,
                'meeting_link' => $this->onlineClass->meeting_link,
                'teacher' => $this->onlineClass->teacher->name,
                'subject' => $this->onlineClass->subject->name ?? 'General',
            ],
            'action_url' => route('academic.online-classes.show', $this->onlineClass),
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("New Online Class: {$this->onlineClass->title}")
            ->greeting("Hello {$notifiable->name}!")
            ->line("A new online class has been scheduled for you.")
            ->line("**Class Details:**")
            ->line("• **Title:** {$this->onlineClass->title}")
            ->line("• **Teacher:** {$this->onlineClass->teacher->name}")
            ->line("• **Subject:** " . ($this->onlineClass->subject->name ?? 'General'))
            ->line("• **Date & Time:** {$this->onlineClass->start_time->format('M d, Y \a\t g:i A')}")
            ->line("• **Duration:** " . $this->onlineClass->start_time->diffInMinutes($this->onlineClass->end_time) . " minutes")
            ->line("• **Meeting Link:** {$this->onlineClass->meeting_link}")
            ->when($this->onlineClass->meeting_password, function($mail) {
                return $mail->line("• **Meeting Password:** {$this->onlineClass->meeting_password}");
            })
            ->when($this->onlineClass->instructions, function($mail) {
                return $mail->line("• **Instructions:** {$this->onlineClass->instructions}");
            })
            ->action('Join Class', $this->onlineClass->meeting_link)
            ->line('Please join the class on time. If you have any questions, contact your teacher.')
            ->salutation('Best regards, School Management System');
    }
}
