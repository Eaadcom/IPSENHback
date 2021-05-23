<?php

namespace Database\Seeders;


use App\Models\LikeMatch;
use Illuminate\Database\Seeder;

class LikeMatchesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LikeMatch::factory()
            ->count(rand(5, 10))
            ->create();
    }
}
