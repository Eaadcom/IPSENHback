<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


/**
 * App\Models\Like
 * @method static Builder userInMatch($likeMatchId, int $userId)
 */
class Like extends Model
{
    use HasFactory;

    protected $fillable = [
       'user_id_of_liked_user', 'type'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function target(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id_of_liked_user', 'id');
    }

    public function likeMatch(): BelongsTo
    {
        return $this->belongsTo(LikeMatch::class);
    }

    public function scopeUserInMatch($query, $likeMatchId, $userId)
    {
        return $query->where('liked_match_id', $likeMatchId)->where(function ($query) use ($userId) {
            return $query->where('user_id', $userId)->orWhere('user_id_of_liked_user', $userId);
        });
    }
}
