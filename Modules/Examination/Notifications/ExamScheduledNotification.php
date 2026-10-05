<?php

namespace Modules\Examination\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Modules\Examination\Models\Exam;

class ExamScheduledNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $exam;

    public function __construct(Exam $exam)
    {
        $this->exam = $exam;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toDatabase($notifiable)
    {
        return new DatabaseMessage([
            'title' => 'New Exam Scheduled',
            'body' => "A new exam '{$this->exam->name}' has been scheduled for you.",
            'action_url' => route('examination.online.exam', $this->exam->id),
            'icon' => 'fas fa-graduation-cap',
            'type' => 'exam_scheduled'
        ]);
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Exam Scheduled - ' . $this->exam->name)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A new exam has been scheduled for you.')
            ->line('**Exam Details:**')
            ->line('• **Name:** ' . $this->exam->name)
            ->line('• **Date:** ' . $this->exam->start_date)
            ->line('• **Time:** ' . $this->exam->start_time)
            ->line('• **Duration:** ' . $this->exam->duration_minutes . ' minutes')
            ->line('• **Total Marks:** ' . $this->exam->total_marks)
            ->action('Take Exam', route('examination.online.exam', $this->exam->id))
            ->line('Please ensure you have a stable internet connection and are in a quiet environment.')
            ->line('Good luck with your exam!');
    }

    public function toArray($notifiable)
    {
        return [
            'exam_id' => $this->exam->id,
            'exam_name' => $this->exam->name,
            'exam_date' => $this->exam->start_date,
            'exam_time' => $this->exam->start_time,
            'duration' => $this->exam->duration_minutes,
            'total_marks' => $this->exam->total_marks,
        ];
    }
}
