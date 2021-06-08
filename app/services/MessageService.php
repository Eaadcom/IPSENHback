<?php


namespace App\services;


use App\Events\MessageCreated;
use App\Models\LikeMatch;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class MessageService
{
    public function create(Message $message)
    {
        /** @var LikeMatch $likeMatch */
        $likeMatch = LikeMatch::query()->where('id', $message->like_match_id)->firstOrFail();

        $this->save(
            $message,
            $likeMatch
        );
    }

    public function broadcast(Request $request): Message
    {
        $message = new Message([
            'content' => $request->get('content'),
            'sender_id' => $request->get('sender_id'),
            'like_match_id' => $request->get('like_match_id'),
            'created_at' => Carbon::now()
        ]);

        event(new MessageCreated($message));

        return $message;
    }

    public function save(Message $message, LikeMatch $likeMatch): void
    {
        $message->likeMatch()->associate($likeMatch);
        $message->sender()->associate(auth()->user());

        $message->save();
    }
}
