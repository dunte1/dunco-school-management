<?php

use Illuminate\Support\Facades\Broadcast;

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

// School management specific channels
Broadcast::channel('school.{schoolId}', function ($user, $schoolId) {
    // Check if user has access to this school
    return $user->school_id == $schoolId || $user->hasRole('admin');
});

// Notification channels
Broadcast::channel('notifications.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});

// Chat channels
Broadcast::channel('chat.{roomId}', function ($user, $roomId) {
    // Add your chat room authorization logic here
    return true;
});

