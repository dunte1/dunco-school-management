<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendFcmMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected string $token,
        protected string $title,
        protected string $body
    ) {}

    public function handle(): void
    {
        $serverKey = config('services.fcm.server_key');
        if (!$serverKey) { Log::warning('FCM server key missing'); return; }

        $payload = [
            'to' => $this->token,
            'notification' => [
                'title' => $this->title,
                'body' => $this->body,
            ],
            'data' => [ 'click_action' => 'FLUTTER_NOTIFICATION_CLICK' ],
        ];

        $resp = Http::withToken($serverKey)
            ->acceptJson()->asJson()
            ->post('https://fcm.googleapis.com/fcm/send', $payload);

        if (!$resp->successful()) {
            Log::warning('FCM send failed', ['status' => $resp->status(), 'body' => $resp->body()]);
        }
    }
}


