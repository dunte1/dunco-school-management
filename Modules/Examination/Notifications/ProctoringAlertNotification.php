<?php

namespace Modules\Examination\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Modules\Examination\Models\ExamAttempt;

class ProctoringAlertNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $attempt;
    protected $alertType;
    protected $severity;

    public function __construct(ExamAttempt $attempt, $alertType, $severity = 'medium')
    {
        $this->attempt = $attempt;
        $this->alertType = $alertType;
        $this->severity = $severity;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toDatabase($notifiable)
    {
        $severityIcons = [
            'low' => 'fas fa-info-circle',
            'medium' => 'fas fa-exclamation-triangle',
            'high' => 'fas fa-exclamation-circle',
            'critical' => 'fas fa-times-circle'
        ];

        return new DatabaseMessage([
            'title' => 'Proctoring Alert - ' . ucfirst($this->severity),
            'body' => "Suspicious activity detected during exam '{$this->attempt->exam->name}' for student {$this->attempt->student->name}.",
            'action_url' => route('examination.proctoring.student-details', $this->attempt->id),
            'icon' => $severityIcons[$this->severity] ?? 'fas fa-exclamation-triangle',
            'type' => 'proctoring_alert'
        ]);
    }

    public function toMail($notifiable)
    {
        $severityColors = [
            'low' => 'blue',
            'medium' => 'yellow',
            'high' => 'orange',
            'critical' => 'red'
        ];

        $color = $severityColors[$this->severity] ?? 'yellow';

        return (new MailMessage)
            ->subject('Proctoring Alert - ' . ucfirst($this->severity) . ' - ' . $this->attempt->exam->name)
            ->greeting('Proctoring Alert')
            ->line('A suspicious activity has been detected during an ongoing exam.')
            ->line('**Alert Details:**')
            ->line('• **Student:** ' . $this->attempt->student->name)
            ->line('• **Exam:** ' . $this->attempt->exam->name)
            ->line('• **Alert Type:** ' . ucfirst(str_replace('_', ' ', $this->alertType)))
            ->line('• **Severity:** ' . ucfirst($this->severity))
            ->line('• **Time:** ' . now()->format('Y-m-d H:i:s'))
            ->action('View Student Details', route('examination.proctoring.student-details', $this->attempt->id))
            ->line('Please review the proctoring logs and take appropriate action if necessary.');
    }

    public function toArray($notifiable)
    {
        return [
            'attempt_id' => $this->attempt->id,
            'exam_id' => $this->attempt->exam_id,
            'exam_name' => $this->attempt->exam->name,
            'student_id' => $this->attempt->student_id,
            'student_name' => $this->attempt->student->name,
            'alert_type' => $this->alertType,
            'severity' => $this->severity,
            'timestamp' => now()->toISOString(),
        ];
    }
}
