<?php

use App\Models\Chat;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

//Broadcast::channel('User.{id}', function (User $user, $id) {
//    return (int) $user->id === (int) $id;
//});

Broadcast::channel('Chat.{id}', function(User $user, $id) {
    $chat = Chat::query()
        ->where('id', $id)
        ->first();
    if ($chat->user_id == auth()->id() || $chat->contact_id == auth()->id()) {
        return true;
    }
    return false;
});
