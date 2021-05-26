<?php

namespace Controllers;

use App\Models\User;
use Illuminate\Support\Arr;
use Laravel\Lumen\Testing\DatabaseMigrations;
use TestCase;

class  AuthControllerTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_post_auth_login_returns_status_200_when_logging_with_valid_credential()
    {
        // uses default password: secret
        $user = User::factory()->create();

        $credentials = ['email' => $user->email, 'password' => 'secret'];

        $this->post_auth_login($credentials)->assertResponseOk();

    }

    public function test_post_auth_login_returns_status_401_when_logging_with_invalid_credential()
    {
        // uses default password: secret
        $user = User::factory()->create();

        $credentials = ['email' => $user->email, 'password' => 'not_a_secret'];

        $this->post_auth_login($credentials)->assertResponseStatus(401);

    }

    public function test_post_auth_login_returns_json()
    {
        $user = User::factory()->create();

        $credentials = ['email' => $user->email, 'password' => 'secret'];

        $this->post_auth_login($credentials)->seeJsonStructure([
            'api_token',
            'user' => [
                'email',
                'first_name',
                'middle_name',
                'last_name',
                'date_of_birth',
                'about_me',
                'age_range_bottom',
                'age_range_top',
                'max_distance',
                'interest'
            ]
        ]);
    }

    public function test_post_auth_login_returns_json_with_out_password()
    {
        $user = User::factory()->create();

        $credentials = ['email' => $user->email, 'password' => 'secret'];

        $this->post_auth_register($credentials)->dontSeeJson([
            'user' => ['password']
        ]);
    }


    public function test_post_auth_register_returns_status_200()
    {
        $user = User::factory()->make()
            ->makeVisible('password')
            ->toArray();

        $this->post_auth_register($user)->assertResponseOk();
    }

    public function test_post_auth_register_returns_status_422_when_missing_user_data()
    {
        $missingFields = [
            'password', 'email', 'first_name', 'middle_name', 'last_name', 'date_of_birth',
            'about_me', 'age_range_bottom', 'age_range_top', 'max_distance', 'interest'
        ];

        $user = User::factory()->make()
            ->makeVisible('password')
            ->makeHidden(Arr::random($missingFields))
            ->toArray();

        $this->post_auth_register($user)->assertResponseStatus(422);
    }


    public function test_post_auth_register_returns_json()
    {
        $user = User::factory()->make()
            ->makeVisible('password')
            ->toArray();

        $this->post_auth_register($user)->seeJsonStructure([
            'api_token',
            'api_token',
            'user' => [
                'email',
                'first_name',
                'middle_name',
                'last_name',
                'date_of_birth',
                'about_me',
                'age_range_bottom',
                'age_range_top',
                'max_distance',
                'interest'
            ]
        ]);
    }

    public function test_post_auth_register_returns_json_with_out_password()
    {
        $user = User::factory()->make()
            ->makeVisible('password')
            ->toArray();

        $this->post_auth_register($user)->dontSeeJson([
            'user' => [
                'password'
            ]
        ]);
    }

    public function test_post_auth_register_creates_user()
    {
        $user = User::factory()
            ->make()
            ->makeVisible('password');

        $this->post_auth_register($user->toArray());

        // password is hashed
        // so we hide it to compare it with the database
        $user->makeHidden('password');

        $this->seeInDatabase('users', $user->toArray());
    }

    public function test_post_auth_register_cannot_creates_user_with_existing_email()
    {
        User::factory()->create([
            'email' => 'user@example.com'
        ]);

        $user = User::factory()
            ->make([
                'email' => 'user@example.com',
            ]);

        $this->post_auth_register($user->toArray());


        $this->missingFromDatabase('users', ['email' => $user->email, 'first_name' => $user->first_name]);
    }

    private function post_auth_login(array $data, User $authUser = null)
    {
        return !is_null($authUser)
            ? $this->actingAs($authUser)->post('api/auth/login', $data)
            : $this->post('api/auth/login', $data);
    }

    private function post_auth_register(array $data, User $authUser = null)
    {
        return !is_null($authUser)
            ? $this->actingAs($authUser)->post('api/auth/register', $data)
            : $this->post('api/auth/register', $data);
    }

}
