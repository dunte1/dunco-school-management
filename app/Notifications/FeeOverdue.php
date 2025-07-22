<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Modules\Academic\Models\StudentFee;

class FeeOverdue extends Notification implements ShouldQueue
{
    use Queueable;

    protected $fee;

    public function __construct(StudentFee $fee)
    {
        $this->fee = $fee;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Overdue Fee Notice for ' . $this->fee->student->name)
            ->greeting('Dear Parent/Guardian,')
            ->line('A fee for your child is now overdue:')
            ->line('Student: ' . $this->fee->student->name)
            ->line('Category: ' . $this->fee->category)
            ->line('Amount: ' . number_format($this->fee->amount, 2))
            ->line('Due Date: ' . ($this->fee->due_date ? $this->fee->due_date->format('Y-m-d') : 'N/A'))
            ->action('View Details', url('/portal'))
            ->line('Please log in to the parent portal for more information.');
    }
} 