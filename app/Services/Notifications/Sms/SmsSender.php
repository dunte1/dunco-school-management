<?php

namespace App\Services\Notifications\Sms;

use Illuminate\Support\Facades\Log;

class SmsSender
{
    public function send(string $to, string $message): string
    {
        $driver = config('notifications.sms.driver', 'log');
        return match ($driver) {
            'africas_talking' => $this->sendViaAfricasTalking($to, $message),
            'twilio' => $this->sendViaTwilio($to, $message),
            'log' => $this->sendViaLog($to, $message),
            default => $this->sendViaLog($to, $message),
        };
    }

    private function sendViaAfricasTalking(string $to, string $message): string
    {
        $username = config('notifications.sms.africas_talking.username');
        $apiKey = config('notifications.sms.africas_talking.api_key');
        $from = config('notifications.sms.africas_talking.from');
        if (!$username || !$apiKey) {
            return $this->sendViaLog($to, $message);
        }
        try {
            $client = new \GuzzleHttp\Client(['base_uri' => 'https://api.africastalking.com']);
            $resp = $client->post('/version1/messaging', [
                'headers' => [
                    'apiKey' => $apiKey,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
                'form_params' => [
                    'username' => $username,
                    'to' => $to,
                    'message' => $message,
                    'from' => $from,
                ],
                'timeout' => 10,
            ]);
            return (string) $resp->getBody();
        } catch (\Throwable $e) {
            Log::error('Africa\'s Talking SMS error', ['error' => $e->getMessage()]);
            return 'africas_talking_error: '.$e->getMessage();
        }
    }

    private function sendViaTwilio(string $to, string $message): string
    {
        $sid = config('notifications.sms.twilio.sid');
        $token = config('notifications.sms.twilio.token');
        $from = config('notifications.sms.twilio.from');
        if (!$sid || !$token || !$from) {
            return $this->sendViaLog($to, $message);
        }
        try {
            $client = new \GuzzleHttp\Client(['base_uri' => 'https://api.twilio.com']);
            $resp = $client->post("/2010-04-01/Accounts/{$sid}/Messages.json", [
                'auth' => [$sid, $token],
                'form_params' => [
                    'From' => $from,
                    'To' => $to,
                    'Body' => $message,
                ],
                'timeout' => 10,
            ]);
            return (string) $resp->getBody();
        } catch (\Throwable $e) {
            Log::error('Twilio SMS error', ['error' => $e->getMessage()]);
            return 'twilio_error: '.$e->getMessage();
        }
    }

    private function sendViaLog(string $to, string $message): string
    {
        Log::info('SMS (log driver)', ['to' => $to, 'message' => $message]);
        return 'logged';
    }
}


