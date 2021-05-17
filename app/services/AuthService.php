<?php

namespace App\services;

use App\Models\User;
use Illuminate\Validation\UnauthorizedException;

class AuthService
{

    public function login(array $credentials): string
    {
        if (!$token = auth()->attempt($credentials)) {
            throw new UnauthorizedException();
        }

        return $token;
    }

    public function register(array $data): User
    {
        $user = new User($data);
        $user->password = app('hash')->make($data['password']);
        $user->save();

        return $user;
    }

}
