<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Modules\Academic\Models\StudentPayment;

class PaymentReceipt extends Notification implements ShouldQueue
{
    use Queueable;

    protected $payment;

    public function __construct(StudentPayment $payment)
    {
        $this->payment = $payment;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Payment Receipt for ' . $this->payment->fee->student->name)
            ->greeting('Dear Parent/Guardian,')
            ->line('A payment has been recorded for your child:')
            ->line('Student: ' . $this->payment->fee->student->name)
            ->line('Category: ' . $this->payment->fee->category)
            ->line('Amount Paid: ' . number_format($this->payment->amount, 2))
            ->line('Payment Date: ' . ($this->payment->payment_date ? $this->payment->payment_date : 'N/A'))
            ->action('View Details', url('/portal'))
            ->line('Thank you for your payment.');
    }
} 