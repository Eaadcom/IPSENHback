<?php


namespace App\services;

use App\Models\Like;
use App\Models\LikeMatch;
use Illuminate\Database\Eloquent\Collection;
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

    public function getById($id)
    {
        return LikeMatch::query()->findOrFail($id);
    }

    public function getAllLikeMatchesOfAuthUser(): Collection
    {
        return LikeMatch::query()->whereIn('id',
            Like::query()
                ->select('like_match_id')
                ->where('user_id', auth()->id())
                ->orWhere('user_id_of_liked_user', auth()->id())
        )->get();
    }
}
