<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('User.{id}', function (User $user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('Chat.{id}', function(User $user, $id) {
    // for testing always allow
    return true;
});
