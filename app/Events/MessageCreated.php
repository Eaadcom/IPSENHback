<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageCreated extends Event implements ShouldBroadcast
{
    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('messages.'.$this->message->like_match_id);
    }

    public function broadcastAs(): string
    {
        return 'my-event';
    }
}
