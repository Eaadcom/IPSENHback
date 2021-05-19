<?php

namespace Database\Seeders;

use App\Models\Codesnippet;
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
        Codesnippet::factory()->create();
    }
}
