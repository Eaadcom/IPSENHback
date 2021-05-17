<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Lumen\Auth\Authorizable;
use phpDocumentor\Reflection\Types\Mixed_;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable, Authorizable, HasFactory;

    protected $fillable = [
        'password', 'email', 'first_name', 'middle_name', 'last_name', 'date_of_birth',
        'about_me', 'age_range_bottom', 'age_range_top', 'max_distance', 'interest'
    ];

    protected $hidden = [
        'password',
    ];

    public function codesnippets(): HasMany
    {
        return $this->hasMany(Codesnippet::class);
    }

    public function givenLikes(): BelongsToMany
    {
        return $this->belongsToMany(Like::class);
    }

    public function receivedLikes(): BelongsToMany
    {
        return $this->belongsToMany(Like::class);
    }

    public function sendMessages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }
}
