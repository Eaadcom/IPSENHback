<?php

namespace Database\Factories;

use App\Models\Match;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition(): array
    {
    	return [
    	    'match_id' => Match::factory()->create()->id,
            'sender_id' => User::factory()->create()->id,
            'content' => $this->faker->paragraph(),
    	];
    }
}
