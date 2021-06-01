<?php

namespace App\Events;

use App\Models\LikeMatch;
use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageCreated extends Event implements ShouldBroadcast
{
    public $content;
    public $like_match_id;
    public $sender_id;
    public $created_at;

    public function __construct(Message $message, LikeMatch $likeMatch)
    {
        $this->content = $message->content;
        $this->like_match_id = $likeMatch->id;
        $this->sender_id = $message->sender_id;
        $this->created_at = $message->created_at;
    }

    public function broadcastOn(): Channel
    {
        return new Channel("messages.{$this->like_match_id}");
    }

    public function broadcastAs(): string
    {
        return 'my-event';
    }
}
