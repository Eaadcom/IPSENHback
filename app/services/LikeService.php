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
        $like->fill($data);

        $like->save();
    }

    public function checkIfThereIsAMatch(array $users)
    {
        $like = Like::where('user_id', '=', $users[1])
                ->where('user_id_of_liked_user', '=', $users[0])
                ->get()[0];

        return $like["type"] == 'like' && $like["liked_back_type"] == 'like';
    }

    public function checkIfLikeExists(array $data): bool
    {
        // != '[]' moet nog worden weggehaald
        return (Like::where('user_id', '=', $data['user_id_of_liked_user'])
            ->where('user_id_of_liked_user', '=', $data['user_id'])
            ->get()) != '[]';
    }

    public function returnLike(array $data)
    {
        Like::where('user_id', '=', $data['user_id_of_liked_user'])
            ->where('user_id_of_liked_user', '=', $data['user_id'])
            ->update(['liked_back_type' => $data['type']]);
    }
}
