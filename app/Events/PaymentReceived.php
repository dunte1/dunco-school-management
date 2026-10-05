<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Modules\Finance\Models\Payment;

class PaymentReceived implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $payment;
    public $student;
    public $action;

    public function __construct(Payment $payment, $action = 'received')
    {
        $this->payment = $payment;
        $this->student = $payment->student;
        $this->action = $action;
    }

    public function broadcastOn()
    {
        return [
            new PrivateChannel('user.' . $this->student->user_id),
            new PrivateChannel('finance.admin'),
            new PrivateChannel('class.' . $this->student->class_id),
        ];
    }

    public function broadcastWith()
    {
        return [
            'type' => 'payment_received',
            'payment_id' => $this->payment->id,
            'student_id' => $this->student->id,
            'student_name' => $this->student->user->name,
            'amount' => $this->payment->amount,
            'payment_method' => $this->payment->payment_method,
            'status' => $this->payment->status,
            'reference' => $this->payment->reference,
            'fee_type' => $this->payment->feeType->name ?? 'Unknown',
            'action' => $this->action,
            'timestamp' => now()->toISOString(),
        ];
    }
}
