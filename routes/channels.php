<?php

use App\Models\Chat\Chat;
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

//Broadcast::routes(['middleware' => ['auth.api']]); // Use the jwt.auth middleware for authenticating channels
//Broadcast::routes();

// Define your private channels here
//dd($user);
Broadcast::channel('chat.{chatId}', function ($user, $chatId) {
    return Chat::where('id', $chatId)->where(function ($q) use ($user) {
        $q->where('customer_id', $user->id)->orWhere('vendor_id', $user->id);
    })->exists()?$user:null;
});


/*Broadcast::channel('user.{userId}', function ($user, $userId) {
    return true; //(int) $user->id === (int) $userId;
});*/
