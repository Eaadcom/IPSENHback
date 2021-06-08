<?php

namespace Database\Seeders;

use App\Models\Like;
use App\Models\LikeMatch;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class LikeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();

        LikeMatch::all()->each(function (LikeMatch $likeMatch) use ($users) {

            $user = $users->random();
            $target = $users->where('id', '!=', $user->id)->random();

            Like::factory()->liked()->create([
                'user_id' => $user->id,
                'user_id_of_liked_user' => $target->id,
                'like_match_id' => $likeMatch->id,
            ]);

        });
    }
}
