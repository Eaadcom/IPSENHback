<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class LikeMatch extends Model
{
    use SoftDeletes, HasFactory;

    public function like(): HasOne
    {
        return $this->hasOne(Like::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}
