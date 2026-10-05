<?php

namespace Modules\Examination\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Modules\Examination\Models\Exam;

class ExamReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $exam;
    protected $reminderType;

    public function __construct(Exam $exam, $reminderType = '24_hours')
    {
        $this->exam = $exam;
        $this->reminderType = $reminderType;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toDatabase($notifiable)
    {
        $timeRemaining = $this->getTimeRemaining();
        
        return new DatabaseMessage([
            'title' => 'Exam Reminder',
            'body' => "Your exam '{$this->exam->name}' starts in {$timeRemaining}.",
            'action_url' => route('examination.online.exam', $this->exam->id),
            'icon' => 'fas fa-clock',
            'type' => 'exam_reminder'
        ]);
    }

    public function toMail($notifiable)
    {
        $timeRemaining = $this->getTimeRemaining();
        
        return (new MailMessage)
            ->subject('Exam Reminder - ' . $this->exam->name . ' starts in ' . $timeRemaining)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('This is a reminder that your exam is starting soon.')
            ->line('**Exam Details:**')
            ->line('• **Name:** ' . $this->exam->name)
            ->line('• **Date:** ' . $this->exam->start_date)
            ->line('• **Time:** ' . $this->exam->start_time)
            ->line('• **Duration:** ' . $this->exam->duration_minutes . ' minutes')
            ->line('• **Time Remaining:** ' . $timeRemaining)
            ->action('Take Exam', route('examination.online.exam', $this->exam->id))
            ->line('Please ensure you are ready and have a stable internet connection.')
            ->line('Good luck!');
    }

    public function toArray($notifiable)
    {
        return [
            'exam_id' => $this->exam->id,
            'exam_name' => $this->exam->name,
            'exam_date' => $this->exam->start_date,
            'exam_time' => $this->exam->start_time,
            'reminder_type' => $this->reminderType,
            'time_remaining' => $this->getTimeRemaining(),
        ];
    }

    private function getTimeRemaining()
    {
        switch ($this->reminderType) {
            case '24_hours':
                return '24 hours';
            case '1_hour':
                return '1 hour';
            case '15_minutes':
                return '15 minutes';
            default:
                return 'soon';
        }
    }
}
