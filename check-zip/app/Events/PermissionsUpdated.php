<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PermissionsUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $roleId;
    public $affectedUserIds;

    /**
     * Create a new event instance.
     */
    public function __construct($roleId, $affectedUserIds = [])
    {
        $this->roleId = $roleId;
        $this->affectedUserIds = $affectedUserIds;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('permissions'),
        ];
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'role_id' => $this->roleId,
            'affected_user_ids' => $this->affectedUserIds,
            'timestamp' => now()->toISOString(),
            'message' => 'Permissions have been updated. Please refresh your sidebar.'
        ];
    }
} 