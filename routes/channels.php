<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

use Illuminate\Support\Facades\Broadcast;


Broadcast::channel('App.Chat.{conversationId}', function ($user, $conversationId) {
    return true;
//    return \App\Models\Conversation::where('pairing_with', $user->id)->where('id', $conversationId)->count() > 0;
//    return (int) $user->id;
});
