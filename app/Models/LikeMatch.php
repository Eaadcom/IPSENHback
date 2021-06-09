<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class LikeMatch extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'deleted_at'
    ];

    protected $with = [
        'messages'
    ];

    protected $appends = [
        'matched_user'
    ];

    protected $hidden = [
      'like'
    ];

    public function like(): HasOne
    {
        return $this->hasOne(Like::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function getMatchedUserAttribute()
    {
        $like = $this->like;

        $user = auth()->id() == $like->user_id ? $like->target : $like->user;

        return [
            'id' => $user->id,
            'first_name' => $user->first_name,
            'middle_name' => $user->first_name,
            'last_name' => $user->last_name,
            'about_me' => $user->about_me
        ];
    }
}
