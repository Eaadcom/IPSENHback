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
            'user_id' => User::factory()->create()->id,
            'user_id_of_liked_user' => User::factory()->create()->id,
            'type' => $this->faker->randomElement(['like', 'dislike', 'super']),
            'like_match_id' => LikeMatch::factory()->create()->id,
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
