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

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory;

    protected $fillable = [
        'password', 'email', 'first_name', 'middle_name', 'last_name', 'date_of_birth',
        'about_me', 'age_range_bottom', 'age_range_top', 'max_distance', 'interest'
    ];

    public static function find(int $id)
    {
        return User::find($id);
    }

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

}
