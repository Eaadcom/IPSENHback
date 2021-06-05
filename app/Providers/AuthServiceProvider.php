<?php

namespace App\Providers;

use App\guard\JwtGuard;
use App\Models\User;
use App\services\JwtService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Auth::extend('jwt', function ($app, $name, array $config) {

            return new JwtGuard(
                Auth::createUserProvider($config['provider']),
                $this->getJwtConfig()
            );
        });
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        $this->app['auth']->viaRequest('api', function ($request) {

            if (!is_null($request->bearerToken())) {
                return User::where('api_token', $request->bearerToken())->first();
            }

        });
    }

    private function getJwtConfig()
    {
        return Configuration::forSymmetricSigner(
            new Sha256(),
            InMemory::base64Encoded('cjgqNCovTCgmR3ZnJz5DOSVpQGt4Qjk8Vzg0YG9aTWAoJyBKMXY/MWxDIQ==')
        );
    }
}
