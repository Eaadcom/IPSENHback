<?php

namespace Database\Factories;

use App\Models\Like;
use App\Models\LikeMatch;
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
            'match_id' => $this->faker->boolean($chanceOfGettingTrue = 33) ? LikeMatch::factory()->create()->id : null,
    	];
    }

    public function liked(LikeMatch $match)
    {
        return $this->state(function (array $attributes) use ($match){
            return [
                'type' => 'like',
            ];
        });
    }

    public function disliked(LikeMatch $match)
    {
        return $this->state(function (array $attributes) use ($match){
            return [
                'type' => 'disliked',
            ];
        });
    }

    public function super(LikeMatch $match)
    {
        return $this->state(function (array $attributes) use ($match){
            return [
                'type' => 'super',
            ];
        });
    }
}
