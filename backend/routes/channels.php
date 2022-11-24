<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Notification;

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

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('messages.{id}', function ($user, $id) {
    return $user->id === (int) $id;
});

Broadcast::channel('messagesstatusread.{id}', function ($user, $id) {
    return $user->id === (int) $id;
});

Broadcast::channel('friendship.{id}', function ($user, $id) {
    return $user->id === (int) $id;
});

Broadcast::channel('friendship-remove.{id}', function ($user, $id) {
    return $user->id === (int) $id;
});

Broadcast::channel('dataAboutMe.{id}', function ($user, $id) {
    return $user->id === (int) $id;
});

//channel getCountAllUnreadMessages
Broadcast::channel('allUnreadMessages.{id}', function ($user, $id) {
    return $user->id === (int) $id;
});

//channel for Notifications
Broadcast::channel('notif-channel.{id}', function ($user, Notification $notifications) {
    return $user->id === $notifications->to;
    // return $user->id === (int) $id;
});

// This is only for testing purposes
Broadcast::channel('pchat', function ($user) {
    return $user;
});

Broadcast::channel('postdata', function ($allDataPost) {
    return $allDataPost;
});

Broadcast::channel('messagereaction', function ($allDataMessage) {
    return $allDataMessage;
});

// Broadcast::channel('pchat', function ($user) {
//     return auth()->$user->name;
// });
//
// Broadcast::channel('comment-channel', function () {
//     return true;
// });
