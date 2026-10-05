<?php

namespace App\Observers;

use App\Events\PaymentReceived;
use App\Jobs\SendFcmMessage;
use Modules\Finance\Models\Payment;
use Illuminate\Support\Facades\Log;

class PaymentObserver
{
    /**
     * Handle the Payment "created" event.
     */
    public function created(Payment $payment): void
    {
        try {
            // Broadcast real-time event
            event(new PaymentReceived($payment, 'created'));

            // Send push notification to student/parent
            $this->sendPaymentNotification($payment, 'Payment received');

            Log::info("Payment created and broadcasted for student: {$payment->student->user->name}");
        } catch (\Exception $e) {
            Log::error("Failed to broadcast payment creation: " . $e->getMessage());
        }
    }

    /**
     * Handle the Payment "updated" event.
     */
    public function updated(Payment $payment): void
    {
        try {
            // Broadcast real-time event
            event(new PaymentReceived($payment, 'updated'));

            // Send push notification if status changed
            if ($payment->wasChanged('status')) {
                $this->sendPaymentNotification($payment, 'Payment status updated');
            }

            Log::info("Payment updated and broadcasted for student: {$payment->student->user->name}");
        } catch (\Exception $e) {
            Log::error("Failed to broadcast payment update: " . $e->getMessage());
        }
    }

    /**
     * Handle the Payment "deleted" event.
     */
    public function deleted(Payment $payment): void
    {
        try {
            // Broadcast real-time event
            event(new PaymentReceived($payment, 'deleted'));

            Log::info("Payment deleted and broadcasted for student: {$payment->student->user->name}");
        } catch (\Exception $e) {
            Log::error("Failed to broadcast payment deletion: " . $e->getMessage());
        }
    }

    /**
     * Send push notification for payment changes
     */
    private function sendPaymentNotification(Payment $payment, string $title): void
    {
        try {
            $student = $payment->student;
            $user = $student->user;
            $feeType = $payment->feeType;

            // Send to student
            if ($user->device_token) {
                $message = "Payment of {$payment->amount} for {$feeType->name} has been {$payment->status}";
                dispatch(new SendFcmMessage($user->device_token, $title, $message));
            }

            // Send to parent if student is a minor
            if ($student->parent && $student->parent->device_token) {
                $message = "Payment of {$payment->amount} for {$student->user->name}'s {$feeType->name} has been {$payment->status}";
                dispatch(new SendFcmMessage($student->parent->device_token, $title, $message));
            }
        } catch (\Exception $e) {
            Log::error("Failed to send payment notification: " . $e->getMessage());
        }
    }
}
