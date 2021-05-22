<?php


namespace App\services;


use App\Models\LikeMatch;
use Carbon\Carbon;

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

        LikeMatch::where('id',$id)
            ->update(['deleted_at'=>Carbon::now()]);

    }

}
