<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Codesnippet extends Model
{
    use HasFactory;

    protected $fillable = [
        'content', 'language', 'theme'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
