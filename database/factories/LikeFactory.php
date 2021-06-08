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
            'user_id' => User::factory(),
            'user_id_of_liked_user' => User::factory(),
            'type' => $this->faker->randomElement(['like', 'dislike', 'super']),
            'like_match_id' => LikeMatch::factory(),
        ];
    }

    public function liked()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'like',
            ];
        });
    }

    public function disliked()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'disliked',
            ];
        });
    }

    public function super()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'super',
            ];
        });
    }
}
