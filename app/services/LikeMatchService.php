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
        return LikeMatch::factory()->create()->id;
    }

    public function delete($id)
    {

        return LikeMatch::query()->findOrFail($id)
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
