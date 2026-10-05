<?php

namespace Modules\Examination\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Modules\Examination\Models\ExamAttempt;

class ExamCompletedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $attempt;

    public function __construct(ExamAttempt $attempt)
    {
        $this->attempt = $attempt;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toDatabase($notifiable)
    {
        $percentage = $this->attempt->total_marks > 0 
            ? round(($this->attempt->obtained_marks / $this->attempt->total_marks) * 100, 2) 
            : 0;

        return new DatabaseMessage([
            'title' => 'Exam Completed',
            'body' => "You have completed the exam '{$this->attempt->exam->name}' with {$percentage}% score.",
            'action_url' => route('examination.results.show', $this->attempt->id),
            'icon' => 'fas fa-check-circle',
            'type' => 'exam_completed'
        ]);
    }

    public function toMail($notifiable)
    {
        $percentage = $this->attempt->total_marks > 0 
            ? round(($this->attempt->obtained_marks / $this->attempt->total_marks) * 100, 2) 
            : 0;

        $mailMessage = (new MailMessage)
            ->subject('Exam Completed - ' . $this->attempt->exam->name)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Congratulations! You have successfully completed your exam.')
            ->line('**Exam Results:**')
            ->line('• **Exam:** ' . $this->attempt->exam->name)
            ->line('• **Score:** ' . $this->attempt->obtained_marks . ' / ' . $this->attempt->total_marks)
            ->line('• **Percentage:** ' . $percentage . '%')
            ->line('• **Time Taken:** ' . $this->attempt->time_taken_minutes . ' minutes')
            ->line('• **Status:** ' . ucfirst($this->attempt->status));

        if ($this->attempt->exam->show_results_immediately) {
            $mailMessage->action('View Detailed Results', route('examination.results.show', $this->attempt->id));
        } else {
            $mailMessage->line('Your results will be available after grading is complete.');
        }

        return $mailMessage->line('Thank you for taking the exam!');
    }

    public function toArray($notifiable)
    {
        $percentage = $this->attempt->total_marks > 0 
            ? round(($this->attempt->obtained_marks / $this->attempt->total_marks) * 100, 2) 
            : 0;

        return [
            'attempt_id' => $this->attempt->id,
            'exam_id' => $this->attempt->exam_id,
            'exam_name' => $this->attempt->exam->name,
            'score' => $this->attempt->obtained_marks,
            'total_marks' => $this->attempt->total_marks,
            'percentage' => $percentage,
            'time_taken' => $this->attempt->time_taken_minutes,
            'status' => $this->attempt->status,
        ];
    }
}
