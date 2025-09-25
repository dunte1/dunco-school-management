<?php

namespace Modules\Finance\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
// use Twilio\Rest\Client; // For real SMS integration
// use Some\WhatsApp\Provider; // For real WhatsApp integration

class FinanceNotificationService
{
    protected $settings;

    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }

    public function sendPaymentNotification($user, $type, $data)
    {
        // SMS
        if (!empty($this->settings['send_sms_reminders']) && !empty($user->phone)) {
            $this->sendSms($user->phone, $this->buildMessage($type, $data));
        }
        // Email
        if (!empty($this->settings['send_email_reminders']) && !empty($user->email)) {
            $this->sendEmail($user->email, $this->buildMessage($type, $data));
        }
        // WhatsApp
        if (!empty($this->settings['whatsapp_integration']) && !empty($user->phone)) {
            $this->sendWhatsApp($user->phone, $this->buildMessage($type, $data));
        }
        // In-App
        if (!empty($this->settings['in_app_alerts'])) {
            $this->sendInApp($user, $this->buildMessage($type, $data));
        }
    }

    protected function buildMessage($type, $data)
    {
        // Simple message builder (customize as needed)
        switch ($type) {
            case 'payment_success':
                return "Payment of {$data['amount']} received for invoice #{$data['invoice_id']}.";
            case 'payment_due':
                return "Invoice #{$data['invoice_id']} is due. Please make payment.";
            default:
                return "Finance notification.";
        }
    }

    protected function sendSms($phone, $message)
    {
        // TODO: Integrate real SMS provider
        Log::info("[SMS] to $phone: $message");
    }

    protected function sendEmail($email, $message)
    {
        // TODO: Use Laravel Mail for real emails
        Log::info("[Email] to $email: $message");
        // Mail::to($email)->send(new \App\Mail\FinanceNotification($message));
    }

    protected function sendWhatsApp($phone, $message)
    {
        // TODO: Integrate real WhatsApp provider
        Log::info("[WhatsApp] to $phone: $message");
    }

    protected function sendInApp($user, $message)
    {
        // TODO: Implement real in-app notification
        Log::info("[In-App] to user {$user->id}: $message");
    }
} 