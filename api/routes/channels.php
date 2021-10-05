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

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('leave.{email}', function ($user, $email) {
    return $user->email === $email;
    // return true;
}, ['guards' => ['api']]);

Broadcast::channel('chat', function($user){
  return $user->email;
}, ['guards' => ['api']]);

Broadcast::channel('kefu', function($user){
//  return $user->name;
  return $user->email;
}, ['guards' => ['api']]);
