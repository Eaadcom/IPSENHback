<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\services\AuthService;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{

    /**
     * @var AuthService
     */
    private $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new AuthService();
    }

    public function test_service_register_function_stores_user_in_the_database()
    {
        $newUser = User::factory()
            ->make([
                'password' => $this->faker->password(8)
            ])
            ->makeVisible('password');


        $this->service->register(
            new User,
            $newUser->toArray()
        );

        // we hide the password because it not hashed.
        $databaseData = $newUser->makeHidden('password')->toArray();
        $this->seeInDatabase('users', $newUser->makeHidden('password')->toArray());
    }



}
