<?php

namespace App\services;


use App\Models\Like;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Boolean;

class LikeService
{
    public function create(array $data)
    {
        $like = new Like;
        $like->user()
            ->associate(auth()->user())
            ->fill($data);
        $like->save();
    }

    public function assignMatchId(array $data, int $id)
    {
        Like::where('user_id', $data['user_id_of_liked_user'])
            ->where('user_id_of_liked_user', auth()->id())
            ->update(['like_match_id' => $id]);
    }

    public function checkIfThereIsAMatch(int $userIdOfLikedUser)
    {
        $like = Like::where('user_id', auth()->id())
            ->where('user_id_of_liked_user', $userIdOfLikedUser)
            ->first();

        return $like["type"] == 'like' && $like["liked_back_type"] == 'like';
    }

    public function checkIfLikeExists(int $userIdOfLikedUser): bool
    {
        return Like::query()->where('user_id', $userIdOfLikedUser)
            ->where('user_id_of_liked_user', auth()->id())
            ->exists();
    }

    public function returnLike(array $data)
    {
        Like::where('user_id', $data['user_id_of_liked_user'])
            ->where('user_id_of_liked_user', auth()->id())
            ->update(['liked_back_type' => $data['type']]);
    }
}
