<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class RealTimeNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notification;
    public $userId;

    /**
     * Create a new event instance.
     */
    public function __construct($notification, $userId = null)
    {
        $this->notification = $notification;
        $this->userId = $userId ?? Auth::id();
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->userId),
            new Channel('notifications')
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'notification.received';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->notification['id'] ?? null,
            'type' => $this->notification['type'] ?? 'info',
            'title' => $this->notification['title'] ?? 'New Notification',
            'message' => $this->notification['message'] ?? '',
            'icon' => $this->notification['icon'] ?? 'fas fa-bell',
            'url' => $this->notification['url'] ?? null,
            'timestamp' => now()->toISOString(),
            'unread_count' => $this->notification['unread_count'] ?? 0
        ];
    }
}
