<?php

declare(strict_types=1);

namespace Tests\Feature\Http;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{

    public function test_auth_user_endpoint_returns_authenticated_user()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('auth.user'));

        $response->seeJson($user->toArray());
    }

    public function test_logout_removes_authenticated_user()
    {
        $this->markTestSkipped();
        auth()->login(User::factory()->create());

        $this->post(route('auth.logout'));

        $this->assertEquals(null, auth()->user());
    }

    public function test_refresh_generates_new_jwt_token()
    {
        $this->markTestSkipped();
        $token = auth()->login(User::factory()->create());

        $this->post(route('auth.refresh'));

        $this->dontSeeJson(['token' => $token]);
    }

    public function test_refresh_generates_jwt_token()
    {
        $this->markTestSkipped();

        $token = auth()->login(User::factory()->create());

        $this->post(route('auth.refresh'));

        $this->seeJsonStructure(['token']);
    }

    public function test_login_returns_status_200_with_valid_credentials()
    {
        $password = $this->getPassword();

        $user = User::factory()->create([
            'password' => Hash::make($password)
        ]);

        $loginRequest = $this->getLoginRequest([
            'email' => $user->email,
            'password' => $password
        ])->toArray();

        $response = $this->post(route('auth.login'), $loginRequest);

        $response->assertResponseOk();
    }

    public function test_login_returns_status_422_with_missing_email()
    {
        $loginRequest = $this->getLoginRequest()
            ->except('email')
            ->toArray();

        $response = $this->post(route('auth.login'), $loginRequest);

        $response->assertResponseStatus(422);
    }

    public function test_login_returns_status_422_with_invalid_email()
    {
        $loginRequest = $this->getLoginRequest([
            'email' => $this->faker->text(10)
        ])->toArray();

        $response = $this->post(route('auth.login'), $loginRequest);

        $response->assertResponseStatus(422);
    }

    public function test_login_returns_status_422_with_missing_password()
    {
        $loginRequest = $this->getLoginRequest()
            ->except('password')
            ->toArray();

        $response = $this->post(route('auth.login'), $loginRequest);

        $response->assertResponseStatus(422);
    }

    public function test_login_returns_status_422_with_short_password()
    {
        $loginRequest = $this->getLoginRequest([
            'password' => $this->faker->password(6,6)
        ])->toArray();

        $response = $this->post(route('auth.login'), $loginRequest);

        $response->assertResponseStatus(422);
    }

    public function test_login_returns_json_message_indicating_that_email_is_required()
    {
        $loginRequest = $this->getLoginRequest()
            ->except('email')
            ->toArray();

        $response = $this->post(route('auth.login'), $loginRequest);

        $response->seeJson([
            'email' => ['The email field is required.']
        ]);
    }

    public function test_login_returns_json_message_indicating_that_password_is_required()
    {
        $loginRequest = $this->getLoginRequest()
            ->except('password')
            ->toArray();

        $response = $this->post(route('auth.login'), $loginRequest);

        $response->seeJson([
            'password' => ['The password field is required.']
        ]);
    }

    public function test_login_returns_json_message_indicating_that_email_and_password_are_required()
    {
        $response = $this->post(route('auth.login'), []);

        $response->seeJson([
            'email' => ['The email field is required.'],
            'password' => ['The password field is required.']
        ]);
    }

    public function test_login_returns_json_message_indicating_that_user_attempted_with_invalid_credentials()
    {
        $response = $this->post(route('auth.login'), [
            'email' => $this->faker->email,
            'password' => $this->faker->text(20),
        ]);

        $response->seeJson([
            'message' => 'Unauthorized',
        ]);
    }

    public function test_login_returns_json_with_a_jwt_token()
    {
        $password = $this->getPassword();

        $user = User::factory()->create([
            'password' => Hash::make($password)
        ]);

        $loginRequest = $this->getLoginRequest([
            'email' => $user->email,
            'password' => $password
        ])->toArray();

        $response = $this->post(route('auth.login'), $loginRequest);

        $response->seeJsonStructure(['token']);
    }

    public function test_register_returns_status_200_with_valid_request()
    {
        $registrationRequestData = $this->getRegisterRequest()
            ->toArray();

        $this->post(route('auth.register'), $registrationRequestData);

        $this->assertResponseOk();
    }

    public function test_register_persist_user_in_database()
    {
        $registrationRequestData = $this->getRegisterRequest()
            ->toArray();

        $this->post(route('auth.register'), $registrationRequestData);
        unset($registrationRequestData['password']);

        $this->seeInDatabase('users', $registrationRequestData);
    }

    public function test_register_returns_status_422_when_missing_email()
    {
        $registrationRequestData = $this->getRegisterRequest()
            ->except('email')
            ->toArray();

        $this->post(route('auth.register'), $registrationRequestData);

        $this->assertResponseStatus(422);
    }

    public function test_register_returns_status_422_when_missing_password()
    {
        $registrationRequestData = $this->getRegisterRequest()
            ->except('password')
            ->toArray();

        $this->post(route('auth.register'), $registrationRequestData);

        $this->assertResponseStatus(422);
    }

    public function test_register_returns_status_422_when_missing_first_name()
    {
        $registrationRequestData = $this->getRegisterRequest()
            ->except('first_name')
            ->toArray();

        $this->post(route('auth.register'), $registrationRequestData);

        $this->assertResponseStatus(422);
    }

    public function test_register_returns_status_200_when_missing_middle_name()
    {
        $registrationRequestData = $this->getRegisterRequest()
            ->except('middle_name')
            ->toArray();

        $this->post(route('auth.register'), $registrationRequestData);

        $this->assertResponseOk();
    }

    public function test_register_returns_status_422_when_missing_last_name()
    {
        $registrationRequestData = $this->getRegisterRequest()
            ->except('last_name')
            ->toArray();

        $this->post(route('auth.register'), $registrationRequestData);

        $this->assertResponseStatus(422);
    }

    public function test_register_returns_status_422_when_missing_gender()
    {
        $registrationRequestData = $this->getRegisterRequest()
            ->except('gender')
            ->toArray();

        $this->post(route('auth.register'), $registrationRequestData);

        $this->assertResponseStatus(422);
    }

    public function test_register_returns_status_422_when_missing_date_of_birth()
    {
        $registrationRequestData = $this->getRegisterRequest()
            ->except('date_of_birth')
            ->toArray();

        $this->post(route('auth.register'), $registrationRequestData);

        $this->assertResponseStatus(422);
    }

    public function test_register_returns_status_200_when_missing_about_me()
    {

        $registrationRequestData = $this->getRegisterRequest()
            ->except('about_me')
            ->toArray();

        $this->post(route('auth.register'), $registrationRequestData);

        $this->assertResponseOk();
    }

    public function test_register_returns_status_200_when_missing_age_rage_top()
    {
        $registrationRequestData = $this->getRegisterRequest()
            ->except('age_range_top')
            ->toArray();

        $this->post(route('auth.register'), $registrationRequestData);

        $this->assertResponseOk();
    }

    public function test_register_returns_status_200_when_missing_age_range_bottom()
    {
        $registrationRequestData = $this->getRegisterRequest()
            ->except('age_range_bottom')
            ->toArray();

        $this->post(route('auth.register'), $registrationRequestData);

        $this->assertResponseOk();
    }

    public function test_register_returns_status_200_when_missing_max_distance()
    {
        $registrationRequestData = $this->getRegisterRequest()
            ->except('max_distance')
            ->toArray();

        $this->post(route('auth.register'), $registrationRequestData);

        $this->assertResponseOk();
    }

    public function test_register_returns_status_200_when_missing_interest()
    {
        $registrationRequestData = $this->getRegisterRequest()
            ->except('interest')
            ->toArray();

        $this->post(route('auth.register'), $registrationRequestData);

        $this->assertResponseOk();
    }

    public function test_register_returns_json_message_indicating_that_email_is_required()
    {
        $registrationRequestData = $this->getRegisterRequest()
            ->except('email')
            ->toArray();

        $this->post(route('auth.register'), $registrationRequestData);

        $this->seeJson([
            'email' => ['The email field is required.'],
        ]);
    }

    public function test_register_returns_json_message_indicating_that_password_is_required()
    {
        $registrationRequestData = $this->getRegisterRequest()
            ->except('password')
            ->toArray();

        $this->post(route('auth.register'), $registrationRequestData);

        $this->seeJson([
            'password' => ['The password field is required.'],
        ]);
    }

    public function test_register_returns_json_message_indicating_that_first_name_is_required()
    {
        $registrationRequestData = $this->getRegisterRequest()
            ->except('first_name')
            ->toArray();

        $this->post(route('auth.register'), $registrationRequestData);

        $this->seeJson([
            'first_name' => ['The first name field is required.'],
        ]);
    }

    public function test_register_returns_json_message_indicating_that_gender_is_required()
    {
        $registrationRequestData = $this->getRegisterRequest()
            ->except('gender')
            ->toArray();

        $this->post(route('auth.register'), $registrationRequestData);

        $this->seeJson([
            'gender' => ['The gender field is required.'],
        ]);
    }

    public function test_register_returns_json_message_indicating_that_date_of_birth_is_required()
    {
        $registrationRequestData = $this->getRegisterRequest()
            ->except('date_of_birth')
            ->toArray();

        $this->post(route('auth.register'), $registrationRequestData);

        $this->seeJson([
            'date_of_birth' => ['The date of birth field is required.'],
        ]);
    }

    public function test_register_returns_json_message_indicating_that_email_is_invalid()
    {
        $registrationRequestData = $this->getRegisterRequest([
            'email' => $this->faker->text(10)
        ])->toArray();

        $this->post(route('auth.register'), $registrationRequestData);

        $this->seeJson([
            'email' => ['The email must be a valid email address.'],
        ]);
    }


    public function test_register_returns_json_message_indicating_that_password_must_be_at_least_be_8_characters_long()
    {
        $registrationRequestData = $this->getRegisterRequest([
            'password' => $this->faker->password(6, 6)
        ])->toArray();

        $this->post(route('auth.register'), $registrationRequestData);

        $this->seeJson([
            'password' => ['The password must be at least 8 characters.'],
        ]);
    }

    public function test_register_returns_json_message_indicating_that_first_name_must_be_at_least_be_3_characters_long()
    {
        $registrationRequestData = $this->getRegisterRequest([
            'first_name' => 'bo',
        ])->toArray();

        $this->post(route('auth.register'), $registrationRequestData);

        $this->seeJson([
            'first_name' => ['The first name must be at least 3 characters.'],
        ]);
    }

    public function test_register_returns_json_message_indicating_that_middle_name_must_be_at_least_be_3_characters_long()
    {
        $registrationRequestData = $this->getRegisterRequest([
            'middle_name' => 'bo',
        ])->toArray();

        $this->post(route('auth.register'), $registrationRequestData);

        $this->seeJson([
            'middle_name' => ['The middle name must be at least 3 characters.'],
        ]);
    }


    public function test_register_returns_json_message_indicating_that_last_name_must_be_at_least_be_3_characters_long()
    {
        $registrationRequestData = $this->getRegisterRequest([
            'last_name' => 'bo',
        ])->toArray();

        $this->post(route('auth.register'), $registrationRequestData);

        $this->seeJson([
            'last_name' => ['The last name must be at least 3 characters.'],
        ]);
    }

    public function test_register_returns_json_message_indicating_that_date_of_birt_must_be_a_valid_date()
    {
        $registrationRequestData = $this->getRegisterRequest([
            'date_of_birth' => $this->faker->word,
        ])->toArray();

        $this->post(route('auth.register'), $registrationRequestData);

        $this->seeJson([
            'date_of_birth' => ['The date of birth is not a valid date.'],
        ]);
    }

    public function test_register_returns_json_message_indicating_that_age_range_top_must_be_at_least_18()
    {
        $registrationRequestData = $this->getRegisterRequest([
            'age_range_top' => 17,
        ])->toArray();

        $this->post(route('auth.register'), $registrationRequestData);

        $this->seeJson([
            'age_range_top' => ['The age range top must be at least 18.'],
        ]);
    }

    public function test_register_returns_json_message_indicating_that_age_range_bottom_must_be_at_least_18()
    {
        $registrationRequestData = $this->getRegisterRequest([
            'age_range_bottom' => 17,
        ])->toArray();

        $this->post(route('auth.register'), $registrationRequestData);

        $this->seeJson([
            'age_range_bottom' => ['The age range bottom must be at least 18.'],
        ]);
    }

    public function test_register_returns_json_message_indicating_that_interest_other_than_female_male_or_any_is_not_allowed()
    {
        $registrationRequestData = $this->getRegisterRequest([
            'interest' => 'other than any, femal or male',
        ])->toArray();

        $this->post(route('auth.register'), $registrationRequestData);

        $this->seeJson([
            'interest' => ['The selected interest is invalid.'],
        ]);
    }

    public function test_register_use_default_preferences_when_not_given() {
        $defaultPreferences = $this->getDefaultPreferences();

        $registrationRequestData = $this->getRegisterRequest()
            ->toArray();

        $this->post(route('auth.register'), $registrationRequestData);

        $this->seeInDatabase('users', $defaultPreferences);
    }

    public function test_register_use_default_preferences_when_given() {
        $preferences = [
            'about_me'          => $this->faker->text(20),
            'interest'          => $this->faker->randomElement(['female','male', 'any']),
            'age_range_top'     => $this->faker->numberBetween(50,100),
            'age_range_bottom'  => $this->faker->numberBetween(18,49),
        ];

        $registrationRequestData = $this->getRegisterRequest($preferences)
            ->toArray();

        $this->post(route('auth.register'), $registrationRequestData);

        $this->seeInDatabase('users', $preferences);
    }

    private function getRegisterRequest(array $data = []): Collection
    {
        return collect([
            'email' => $this->faker->email,
            'password' => $this->faker->password(8),
            'first_name' => $this->faker->firstName,
            'middle_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'gender' => $this->faker->randomElement(['male', 'female']),
            'date_of_birth' => $this->faker->dateTime(),
        ])->merge($data);
    }

    private function getPassword($passwordLength = 8): string
    {
        return $this->faker->password($passwordLength);
    }

    private function getLoginRequest(array $data = []): collection
    {
        return collect([
            'email' => $this->faker->email,
            'password' => $this->faker->password(8),
        ])->merge($data);
    }

    private function getDefaultPreferences(): array
    {
        return [
            'about_me'          => 'I 💕 Matcher!',
            'interest'          => 'any',
            'age_range_top'     => 100,
            'age_range_bottom'  => 100,
        ];
    }
}
