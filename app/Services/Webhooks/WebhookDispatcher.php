<?php

namespace App\Services\Webhooks;

use App\Models\WebhookEndpoint;
use Illuminate\Support\Facades\Http;

class WebhookDispatcher
{
    public function dispatch(string $event, array $payload): int
    {
        $endpoints = WebhookEndpoint::where('is_active', true)
            ->where(function($q) use($event){
                $q->whereNull('events')->orWhereJsonContains('events', $event);
            })->get();

        $count = 0;
        foreach ($endpoints as $ep) {
            $data = $payload;
            if ($ep->secret) {
                $data['signature'] = hash_hmac('sha256', json_encode($payload), $ep->secret);
            }
            $res = Http::timeout(10)->post($ep->url, $data);
            $count++;
        }
        return $count;
    }
}


