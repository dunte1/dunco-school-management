<?php

namespace Modules\Finance\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Notifications\DatabaseNotification;
use App\Models\User;

class FinanceNotificationService
{
    protected $settings;

    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }

    public function sendPaymentNotification($user, $type, $data)
    {
        $message = $this->buildMessage($type, $data);

        if (!empty($this->settings['send_sms_reminders']) && !empty($user->phone)) {
            $this->sendSms($user->phone, $message);
        }

        if (!empty($this->settings['send_email_reminders']) && !empty($user->email)) {
            $this->sendEmail($user->email, $message);
        }

        if (!empty($this->settings['whatsapp_integration']) && !empty($user->phone)) {
            $this->sendWhatsApp($user->phone, $message);
        }

        if (!empty($this->settings['in_app_alerts'])) {
            $this->sendInApp($user, $message);
        }
    }

    protected function buildMessage($type, $data)
    {
        switch ($type) {
            case 'payment_success':
                return "Payment of {$data['amount']} received for invoice #{$data['invoice_id']}.";
            case 'payment_due':
                return "Invoice #{$data['invoice_id']} is due. Please make payment.";
            case 'payment_overdue':
                return "Invoice #{$data['invoice_id']} is overdue. Please make payment immediately.";
            case 'fee_assigned':
                return "A new fee of {$data['amount']} has been assigned. Due date: {$data['due_date']}.";
            default:
                return "Finance notification.";
        }
    }

    protected function sendSms($phone, $message)
    {
        try {
            Log::info('[FinanceNotificationService] SMS sent', [
                'phone' => $phone,
                'message' => $message,
                'channel' => 'sms',
            ]);

            // Integrate with your SMS provider (e.g., Twilio, Africa's Talking, etc.)
            // Example with Twilio:
            // $client = new \Twilio\Rest\Client(config('services.twilio.sid'), config('services.twilio.token'));
            // $client->messages->create($phone, [
            //     'from' => config('services.twilio.from'),
            //     'body' => $message,
            // ]);
        } catch (\Exception $e) {
            Log::error('[FinanceNotificationService] SMS failed', [
                'phone' => $phone,
                'error' => $e->getMessage(),
            ]);
        }
    }

    protected function sendEmail($email, $message)
    {
        try {
            Log::info('[FinanceNotificationService] Email sent', [
                'email' => $email,
                'message' => $message,
                'channel' => 'email',
            ]);

            // Use Laravel Mail - create a Mailable class for production use
            // Mail::to($email)->send(new \App\Mail\FinanceNotificationMail($message));

            // For now, use raw markdown mail
            Mail::raw($message, function ($mail) use ($email) {
                $mail->to($email)
                    ->subject('Finance Notification - Dunco School')
                    ->from(config('mail.from.address'), config('mail.from.name'));
            });
        } catch (\Exception $e) {
            Log::error('[FinanceNotificationService] Email failed', [
                'email' => $email,
                'error' => $e->getMessage(),
            ]);
        }
    }

    protected function sendWhatsApp($phone, $message)
    {
        try {
            Log::info('[FinanceNotificationService] WhatsApp message queued', [
                'phone' => $phone,
                'message' => $message,
                'channel' => 'whatsapp',
            ]);

            // Integrate with WhatsApp Business API provider
            // Example with a generic API:
            // $response = Http::withToken(config('services.whatsapp.token'))
            //     ->post('https://graph.facebook.com/v17.0/' . config('services.whatsapp.phone_number_id') . '/messages', [
            //         'messaging_product' => 'whatsapp',
            //         'to' => $phone,
            //         'type' => 'text',
            //         'text' => ['body' => $message],
            //     ]);
        } catch (\Exception $e) {
            Log::error('[FinanceNotificationService] WhatsApp failed', [
                'phone' => $phone,
                'error' => $e->getMessage(),
            ]);
        }
    }

    protected function sendInApp($user, $message)
    {
        try {
            if ($user instanceof User) {
                $user->notify(new \Modules\Finance\Notifications\FinanceInAppNotification($message));
            } else {
                // Fallback: create a database notification directly
                if (method_exists($user, 'notifications')) {
                    $user->notifications()->create([
                        'type' => 'App\\Notifications\\FinanceNotification',
                        'data' => [
                            'message' => $message,
                            'title' => 'Finance Notification',
                            'type' => 'finance',
                        ],
                    ]);
                }
            }

            Log::info('[FinanceNotificationService] In-app notification sent', [
                'user_id' => $user->id ?? null,
                'message' => $message,
                'channel' => 'in_app',
            ]);
        } catch (\Exception $e) {
            Log::error('[FinanceNotificationService] In-app notification failed', [
                'user_id' => $user->id ?? null,
                'error' => $e->getMessage(),
            ]);
        }
    }
} 