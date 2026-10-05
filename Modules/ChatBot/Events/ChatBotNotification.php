<?php

namespace Modules\ChatBot\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatBotNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $channel;
    public $event;
    public $data;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($channel, $event, $data = [])
    {
        $this->channel = $channel;
        $this->event = $event;
        $this->data = $data;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel($this->channel);
    }

    /**
     * Get the event name to broadcast.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return $this->event;
    }
}