<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class GenericDatabaseNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /** @var array */
    public $payload;

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        // Ensure a consistent shape
        return [
            'type' => $this->payload['type'] ?? 'info',
            'title' => $this->payload['title'] ?? 'Notification',
            'message' => $this->payload['message'] ?? '',
            'url' => $this->payload['url'] ?? null,
            'event_id' => $this->payload['event_id'] ?? null,
            'starts_at' => $this->payload['starts_at'] ?? null,
            'meta' => $this->payload['meta'] ?? [],
        ];
    }
}
