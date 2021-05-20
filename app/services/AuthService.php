<?php

namespace App\services;

use App\Models\Like;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthService
{

    /**
     * @param array $credentials
     * @return User|null
     */
    public function attempt(array $credentials): ?Authenticatable
    {
        $user = $this->retrieveByCredentials($credentials);

        if ($this->validateCredentials($user, $credentials)) {
            return $user;
        }

        return null;
    }

    public function register(array $data): User
    {
        $user = new User($data);
        $user->password = Hash::make($data['password']);
        $user->save();

        return $user;
    }

    public function retrieveByCredentials(array $credentials): Authenticatable
    {

        $query = User::query();

        foreach ($credentials as $key => $value) {
            if (Str::contains($key, 'password')) {
                continue;
            }

            if (is_array($value) || $value instanceof Arrayable) {
                $query->whereIn($key, $value);
            } else {
                $query->where($key, $value);
            }
        }
        return $query->first();
    }

    private function validateCredentials(UserContract $user, array $credentials): bool
    {
        $plain = $credentials['password'];

        return Hash::check($plain, $user->getAuthPassword());
    }

}
