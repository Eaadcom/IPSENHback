<?php

namespace App\services;


use App\Models\Like;

class LikeService
{
    public function create(array $data)
    {
        $this->save(
            new Like,
            $data
        );
    }

    public function save(Like $like, array $data)
    {
        $like->fill($data);

        $like->save();
    }
}
