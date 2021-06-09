<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'email' => 'user@example.com',
        ]);

        User::factory()->create([
            'email' => 'luser@example.com',
        ]);

        User::factory()
            ->count(5)
            ->create();
    }
}
