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
            'api_token' => base64_encode('wachtwoord1')
        ]);

        User::factory()->create([
            'email' => 'luser@example.com',
            'api_token' => base64_encode('wachtwoord2')
        ]);

        User::factory()
            ->count(5)
            ->create();
    }
}
