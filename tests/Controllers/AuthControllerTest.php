<?php

namespace Controllers;

use App\Models\User;
use Faker\Factory;
use Illuminate\Http\Response;
use Laravel\Lumen\Testing\DatabaseMigrations;

class  AuthControllerTest extends \TestCase
{
    use DatabaseMigrations;

    public function test_user_can_create_account()
    {
        $user = User::factory()->make()
            ->makeVisible('password');

        $response = $this->post('auth/register', $user->toArray());

        $response->seeJsonStructure([
            'api_token',
            'user' => [
                'id',
                'email',
                'first_name',
                'middle_name',
                'last_name',
                'date_of_birth',
                'about_me',
                'age_range_bottom',
                'age_range_top',
                'max_distance',
                'interest',
                'updated_at',
                'created_at',
            ]
        ])->assertResponseOk();
    }

    public function test_user_cannot_create_account_with_existing_email()
    {

        User::factory()->create([
            'email' => 'user@email.com'
        ]);

        $user = User::factory()
            ->make(['email' => 'user@email.com'])
            ->makeVisible('password');

        $this->post('auth/register', $user->toArray())
            ->seeJsonEquals([
                'email' => ['The email has already been taken.']
            ])
            ->assertResponseStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_user_can_login_with_valid_credentials()
    {

        $password = $this->faker->word();

        // password = secret
        $user = User::factory()->create([
            'email' => $this->faker->email,
            'password' => app('hash')->make($password)
        ]);

        $this->post('auth/login', [
            'email' => $user->email,
            'password' => $password
        ])->seeJsonStructure([
            'token',
            'token_type',
            'expires_in',
        ])->assertResponseOk();

        $this->seeInDatabase('users', ['email' => $user->email]);
    }

    public function test_user_cannot_login_with_invalid_email()
    {
        $password = $this->faker->word();
        $user = User::factory()->create([
            'email' => $this->faker->email,
            'password' => app('hash')->make($password)
        ]);

        $this->post('auth/login', [
            'email' => 'invalid',
            'password' => $password
        ])->seeJsonEquals([
            'message' => 'Unauthorized'
        ])->assertResponseStatus(Response::HTTP_UNAUTHORIZED);

    }

    public function test_user_cannot_login_with_invalid_password()
    {
        $user = User::factory()->create([
            'email' => $this->faker->email,
            'password' => app('hash')->make($this->faker->word())
        ]);

        $this->post('auth/login', [
            'email' => $user->email,
            'password' => 'invalid'
        ])->seeJsonEquals([
            'message' => 'Unauthorized'
        ])->assertResponseStatus(Response::HTTP_UNAUTHORIZED);
    }

}
