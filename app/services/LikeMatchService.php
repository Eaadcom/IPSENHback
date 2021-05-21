<?php


namespace App\services;


use App\Models\LikeMatch;

class LikeMatchService
{

    public function create()
    {
        $likeMatch =
            new LikeMatch();
        error_log($likeMatch);
        $likeMatch->save();
    }

    public function update(LikeMatch $likematch, array $data)
    {
        $this->save(
            $likematch,
            $data
        );
    }

    public function save(LikeMatch $likematch, array $data)
    {
        $likematch->fill($data);

        $likematch->save();
    }

    public function delete($id)
    {
        LikeMatch::find($id)->delete();
    }

}
