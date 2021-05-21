<?php

namespace App\services;

use App\Models\User;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\UnauthorizedException;

class AuthService
{

    /**
     * @var EloquentUserProvider
     */
    private $eloquentUserProvider;

    public function __construct()
    {
        $this->eloquentUserProvider = new EloquentUserProvider(app('hash'), User::class);
    }

    public function register(array $data): User
    {
        $user = new User($data);

        $user->password = Hash::make($data['password']);
        $user->api_token = base64_encode(Str::random(40));

        $user->save();

        return $user;
    }

    public function login(array $credentials): UserContract
    {
        $user = $this->eloquentUserProvider->retrieveByCredentials($credentials);

        if ($this->hasValidCredentials($user, $credentials)) {
            return $this->updateApiToken($user);

        }

        throw new UnauthorizedException();
    }

    private function hasValidCredentials(?UserContract $user, array $credentials)
    {
        return !is_null($user) && $this->eloquentUserProvider->validateCredentials($user, $credentials);

    }

    private function updateApiToken(UserContract $user): UserContract
    {
        $apiToken = base64_encode(Str::random(40));

        $user->api_token = $apiToken;
        $user->save();

        return $user;
    }
}
