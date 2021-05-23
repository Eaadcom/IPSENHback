<?php

namespace Database\Factories;

use App\Model;
use App\Models\Codesnippet;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CodesnippetFactory extends Factory
{
    protected $model = Codesnippet::class;

    public function definition(): array
    {
        return [
            'content' => $this->faker->paragraph,
            'language' => $this->faker->languageCode,
            'theme' => $this->faker->word,
            'user_id' => User::factory()
        ];
    }
}
