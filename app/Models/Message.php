<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'content'
    ];

    protected $with = [
        'sender'
    ];

    protected $appends = [
        'is_sender'
    ];

    protected $hidden = [
        'sender_id'
    ];

    public function likeMatch(): BelongsTo
    {
        return $this->belongsTo(LikeMatch::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function getIsSenderAttribute(): bool
    {
        return auth()->id() == $this->sender_id;
    }
}
