<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'email' => $this->faker->unique()->safeEmail(),
            'password' => '$2y$10$SPGirhL/vQzvng6aSV1RNubkPFjAmcNUHlJ0y1x7tab84JeAQTjxa', // Wachtwoord, case-sensitive
            'first_name' => $this->faker->firstName(),
            'middle_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'date_of_birth' => $this->faker->dateTime(),
            'about_me' => $this->faker->text(),
            'age_range_bottom' => $this->faker->numberBetween(20, 30),
            'age_range_top' => $this->faker->numberBetween(30, 40),
            'interest' => $this->faker->randomElement(['male', 'female']),
        ];
    }
}
