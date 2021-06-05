<?php

declare(strict_types=1);

namespace App\guard;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Token\Plain;

class JwtGuard implements Guard
{
    use GuardHelpers;

    /**
     * @var Plain
     */
    private $token;
    /**
     * @var Configuration
     */
    private $jwtConfig;

    public function __construct(UserProvider $provider, Configuration $jwtConfig)
    {
        $this->provider = $provider;
        $this->jwtConfig = $jwtConfig;
    }

    public function attempt(array $credentials = [], $remember = false): bool
    {
        $user = $this->provider->retrieveByCredentials($credentials);

        if ($this->hasValidCredentials($user, $credentials)) {
            $this->login($user);
            return true;
        }

        return false;
    }

    private function hasValidCredentials(?Authenticatable $user, array $credentials): bool
    {
        return !is_null($user) && $this->provider->validateCredentials($user, $credentials);
    }

    private function login(Authenticatable $user): void
    {
        $this->token = $this->generateToken($user);
        $this->setUser($user);
    }

    public function generateToken(Authenticatable $user): Plain
    {
        $tokenBuilder = $this->jwtConfig->builder()
            ->issuedBy(config('app.url'))
            ->issuedAt(Carbon::now()->toDateTimeImmutable())
            ->canOnlyBeUsedAfter(Carbon::now()->addMinutes(15)->toDateTimeImmutable());

        $this->appendUserClaims($user, $tokenBuilder);

        return $tokenBuilder->getToken($this->jwtConfig->signer(), $this->jwtConfig->signingKey());
    }

    private function appendUserClaims(Authenticatable $user, Builder $jwtToken): void
    {
        if ($user instanceof User) {
            foreach ($user->getJwtClaims() as $claim => $value) {
                $jwtToken->withClaim($claim, $value);
            }
        }
    }


    public function logout(): void
    {
        $this->token = null;
        $this->user = null;
    }

    /**
     * @return Authenticatable|null
     */
    public function user(): ?Authenticatable
    {
        // We check if user is already defined
        // so that we can limit our database calls.
        if (!is_null($this->user)) {
            return $this->user;
        }

        if (!$this->token) {
            return null;
        }

        $user = $this->getUserByToken();

        $this->setUser($user);

        return $user;
    }

    public function validate(array $credentials = [])
    {
        $user = $this->provider->retrieveByCredentials($credentials);

        return $this->hasValidCredentials($user, $credentials);
    }

    private function getUserByToken(): ?Authenticatable
    {
        $userId = $this->token->claims()->get('id');
        return $this->provider->retrieveById($userId);
    }

    public function getToken(): ?Plain
    {
        return $this->token;
    }

}
