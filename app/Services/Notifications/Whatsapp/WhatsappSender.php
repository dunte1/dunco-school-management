<?php

namespace App\Services\Notifications\Whatsapp;

use Illuminate\Support\Facades\Log;

class WhatsappSender
{
    public function send(string $to, string $message): string
    {
        $driver = config('notifications.whatsapp.driver', 'log');
        return match ($driver) {
            'twilio' => $this->sendViaTwilio($to, $message),
            'log' => $this->sendViaLog($to, $message),
            default => $this->sendViaLog($to, $message),
        };
    }

    private function sendViaTwilio(string $to, string $message): string
    {
        $sid = config('notifications.whatsapp.twilio.sid');
        $token = config('notifications.whatsapp.twilio.token');
        $from = config('notifications.whatsapp.twilio.from'); // e.g., 'whatsapp:+14155238886'
        if (!$sid || !$token || !$from) {
            return $this->sendViaLog($to, $message);
        }
        try {
            $client = new \GuzzleHttp\Client(['base_uri' => 'https://api.twilio.com']);
            $resp = $client->post("/2010-04-01/Accounts/{$sid}/Messages.json", [
                'auth' => [$sid, $token],
                'form_params' => [
                    'From' => $from,
                    'To' => 'whatsapp:'.$to,
                    'Body' => $message,
                ],
                'timeout' => 10,
            ]);
            return (string) $resp->getBody();
        } catch (\Throwable $e) {
            Log::error('Twilio WhatsApp error', ['error' => $e->getMessage()]);
            return 'whatsapp_twilio_error: '.$e->getMessage();
        }
    }

    private function sendViaLog(string $to, string $message): string
    {
        Log::info('WhatsApp (log driver)', ['to' => $to, 'message' => $message]);
        return 'logged';
    }
}

 


