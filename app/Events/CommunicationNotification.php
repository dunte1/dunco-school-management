<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommunicationNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $recipients;
    public $type;

    /**
     * Create a new event instance.
     */
    public function __construct($message, $recipients, $type = 'message')
    {
        $this->message = $message;
        $this->recipients = is_array($recipients) ? $recipients : [$recipients];
        $this->type = $type;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        $channels = [];
        
        // Broadcast to specific user channels
        foreach ($this->recipients as $recipientId) {
            $channels[] = new PrivateChannel('user.' . $recipientId);
        }
        
        // Also broadcast to general notifications channel
        $channels[] = new Channel('notifications');
        
        return $channels;
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'communication.' . $this->type;
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->message['id'] ?? null,
            'type' => $this->type,
            'title' => $this->message['title'] ?? 'New Message',
            'subject' => $this->message['subject'] ?? '',
            'body' => $this->message['body'] ?? '',
            'sender' => $this->message['sender'] ?? null,
            'timestamp' => now()->toISOString(),
            'priority' => $this->message['priority'] ?? 'normal',
            'icon' => $this->getIconForType($this->type)
        ];
    }

    /**
     * Get icon based on message type
     */
    private function getIconForType($type)
    {
        return match($type) {
            'message' => 'fas fa-envelope',
            'broadcast' => 'fas fa-bullhorn',
            'announcement' => 'fas fa-megaphone',
            'urgent' => 'fas fa-exclamation-triangle',
            'system' => 'fas fa-cog',
            default => 'fas fa-bell'
        };
    }
}
