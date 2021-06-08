<?php

use App\Models\Like;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('messages.{$likeMatchId}', function ($likeMatchId) {
    return Like::userInMatch($likeMatchId, auth()->id())->exists();
});

