<?php

namespace App\services;

use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\UnauthorizedException;
use Lcobucci\JWT\Token\Plain;

class AuthService
{
    public function register(User $user, array $data): User
    {
        $user->fill($data);
        $user->password = Hash::make($data['password']);
        $user->save();

        return $user;
    }
}
