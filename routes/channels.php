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

Broadcast::channel('babysitters-notification.{id}', function ($user, $id) {
    return $user->id === $id;
});

Broadcast::channel('babysitters-chat.{chat_id}', function ($user, $chat_id) {
    return \App\Models\Chat::find($chat_id) ? true : false;
});

// Broadcast::channel('ma7ta_mobile-update-location_driver_test', TestChannel::class);

Broadcast::channel('babysitters-online', function ($user) {
    return [
        'id' => $user->id,
        'fullname' => $user->fullname,
        'user_type' => $user->user_type,
        'avatar' => $user->avatar,
        ];
});

