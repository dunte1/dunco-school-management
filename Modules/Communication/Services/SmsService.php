<?php

namespace Modules\Communication\Services;

class SmsService
{
    protected $username;
    protected $apiKey;

    public function __construct($username, $apiKey)
    {
        $this->username = $username;
        $this->apiKey = $apiKey;
    }

    public function send($to, $message)
    {
        $url = 'https://api.africastalking.com/version1/messaging';
        $data = [
            'username' => $this->username,
            'to' => $to,
            'message' => $message,
        ];
        $headers = [
            'apiKey: ' . $this->apiKey,
            'Accept: application/json',
        ];
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
} 