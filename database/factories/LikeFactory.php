<?php

namespace Database\Factories;

use App\Models\Like;
use App\Models\Match;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LikeFactory extends Factory
{
    protected $model = Like::class;

    public function definition(): array
    {

        return [
            'id' => $this->faker->uuid,
            'user_id' => User::factory()->create()->id,
            'user_id_of_liked_user' => User::factory()->create()->id,
            'type' => $this->faker->randomElement(['like', 'dislike', 'super']),
            'match_id' => $this->faker->boolean($chanceOfGettingTrue = 33) ? Match::factory()->create()->id : null,
    	];
    }
}
