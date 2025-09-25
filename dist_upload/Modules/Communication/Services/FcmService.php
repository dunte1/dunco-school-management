<?php

namespace Modules\Communication\Services;

class FcmService
{
    protected $serverKey;

    public function __construct($serverKey)
    {
        $this->serverKey = $serverKey;
    }

    public function send($to, $title, $body)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $fields = [
            'to' => $to,
            'notification' => [
                'title' => $title,
                'body' => $body,
            ],
            'data' => [
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
            ],
        ];
        $headers = [
            'Authorization: key=' . $this->serverKey,
            'Content-Type: application/json',
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
} 