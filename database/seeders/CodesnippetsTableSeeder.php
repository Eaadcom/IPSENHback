<?php

namespace Database\Seeders;

use App\Models\Codesnippet;
use App\Models\User;
use Illuminate\Database\Seeder;

class CodesnippetsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::all()->each(function ($user) {

            Codesnippet::factory()->create([
                'user_id' => $user->id
            ]);

        });
    }
}
