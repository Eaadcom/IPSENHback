<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Like extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'user_id_of_liked_user', 'type'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function target(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id_of_liked_user', 'id');
    }

//    public function match(): BelongsTo
//    {
//        return $this->belongsTo(Match::class);
//    }
}
