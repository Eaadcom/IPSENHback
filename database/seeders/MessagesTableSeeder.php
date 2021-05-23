<?php

namespace Database\Seeders;

use App\Models\LikeMatch;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Seeder;

class MessagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Message::factory()->count(rand(40, 100))->create([
            'sender_id' => function () {
                return User::all()->random();
            },
            'like_match_id' => function () {
                return LikeMatch::all()->random();
            },
        ]);
    }
}
