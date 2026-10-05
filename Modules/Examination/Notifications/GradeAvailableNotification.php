<?php

namespace Modules\Examination\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Modules\Examination\Models\QuestionGrading;

class GradeAvailableNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $grading;

    public function __construct(QuestionGrading $grading)
    {
        $this->grading = $grading;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toDatabase($notifiable)
    {
        $percentage = $this->grading->total_marks > 0 
            ? round(($this->grading->marks_obtained / $this->grading->total_marks) * 100, 2) 
            : 0;

        return new DatabaseMessage([
            'title' => 'Grade Available',
            'body' => "Your grade for question in '{$this->grading->examAttempt->exam->name}' is now available ({$percentage}%).",
            'action_url' => route('examination.results.show', $this->grading->exam_attempt_id),
            'icon' => 'fas fa-star',
            'type' => 'grade_available'
        ]);
    }

    public function toMail($notifiable)
    {
        $percentage = $this->grading->total_marks > 0 
            ? round(($this->grading->marks_obtained / $this->grading->total_marks) * 100, 2) 
            : 0;

        $grade = $this->grading->grade ?? 'N/A';

        return (new MailMessage)
            ->subject('Grade Available - ' . $this->grading->examAttempt->exam->name)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your grade for a question has been published.')
            ->line('**Grade Details:**')
            ->line('• **Exam:** ' . $this->grading->examAttempt->exam->name)
            ->line('• **Question:** ' . substr($this->grading->question->question_text, 0, 100) . '...')
            ->line('• **Score:** ' . $this->grading->marks_obtained . ' / ' . $this->grading->total_marks)
            ->line('• **Percentage:** ' . $percentage . '%')
            ->line('• **Grade:** ' . $grade)
            ->action('View Detailed Results', route('examination.results.show', $this->grading->exam_attempt_id));

        if ($this->grading->feedback) {
            $mailMessage->line('**Feedback:** ' . $this->grading->feedback);
        }

        return $mailMessage->line('Thank you!');
    }

    public function toArray($notifiable)
    {
        $percentage = $this->grading->total_marks > 0 
            ? round(($this->grading->marks_obtained / $this->grading->total_marks) * 100, 2) 
            : 0;

        return [
            'grading_id' => $this->grading->id,
            'exam_id' => $this->grading->examAttempt->exam_id,
            'exam_name' => $this->grading->examAttempt->exam->name,
            'question_id' => $this->grading->question_id,
            'score' => $this->grading->marks_obtained,
            'total_marks' => $this->grading->total_marks,
            'percentage' => $percentage,
            'grade' => $this->grading->grade,
            'feedback' => $this->grading->feedback,
        ];
    }
}
