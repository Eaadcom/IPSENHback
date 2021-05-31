<?php


namespace App\services;


use App\Events\MessageCreated;
use App\Models\LikeMatch;
use App\Models\Message;

class MessageService
{
    public function create(array $data)
    {
        /** @var LikeMatch $likeMatch */
        $likeMatch = LikeMatch::query()->where('id', $data['like_match_id'])->first();

        $message = $this->save(
            new Message,
            $likeMatch,
            $data
        );

        broadcast(new MessageCreated($message, $likeMatch));
    }

    public function save(Message $message, LikeMatch $likeMatch, array $data): Message
    {
        $message->fill($data);

        $message->likeMatch()->associate($likeMatch);
        $message->sender()->associate(auth()->user());

        $message->save();

        return $message;
    }
}
