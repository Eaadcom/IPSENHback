<?php

namespace App\services;


use App\Models\Like;
use Illuminate\Support\Facades\DB;

class LikeService
{
    public function create(array $data)
    {
        $this->save(
            new Like,
            $data
        );
    }

    public function update(Like $like, array $data)
    {
        $this->save(
            $like,
            $data
        );
    }

    public function returnLike(array $data)
    {
        DB::table('likes')
            ->where('user_id', '=', $data['user_id_of_liked_user'])
            ->where('user_id_of_liked_user', '=', $data['user_id'])
            ->update(['liked_back_type' => $data['type']]);
    }

    public function save(Like $like, array $data)
    {
        $like->fill($data);

        $like->save();
    }
}
