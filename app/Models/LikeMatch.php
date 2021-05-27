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

        return auth()->id() == $like->user_id ? $like->target : $like->user;

    }
}
