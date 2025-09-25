<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Modules\Communication\Models\MessageRecipient;

class MessageSent implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $recipient;

    public function __construct(MessageRecipient $recipient)
    {
        $this->recipient = $recipient;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('user.' . $this->recipient->recipient_id);
    }

    public function broadcastWith()
    {
        return [
            'message_id' => $this->recipient->message_id,
            'subject' => $this->recipient->message->subject,
            'body' => $this->recipient->message->body,
            'sender' => $this->recipient->message->sender->name ?? 'Unknown',
            'created_at' => $this->recipient->created_at->toDateTimeString(),
        ];
    }
} 