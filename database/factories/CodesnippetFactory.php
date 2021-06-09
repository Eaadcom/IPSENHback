<?php

namespace Database\Factories;

use App\Models\Codesnippet;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

class CodesnippetFactory extends Factory
{
    protected $model = Codesnippet::class;

    public function definition(): array
    {
        return [
            'content' => function () {
                return $this->getRandomSnippet();
            },
            'language' => $this->faker->randomElement(['javascript', 'python']),
            'theme' => $this->faker->randomElement(['dark', 'light']),
            'user_id' => User::factory()
        ];
    }

    private function getRandomSnippet()
    {
        $snippet = rand(0,2);
        $file = 'codesnippets/snippet_' . $snippet . '.txt';

        return file_get_contents(Storage::path($file));

    }
}
