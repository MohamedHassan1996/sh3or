<?php

namespace App\Http\Controllers\Api\Customer\Chat;

use App\Events\UserStatusEvent;
use App\Http\Controllers\Controller;
use App\Models\Chat\Chat;
use App\Models\User;
use Illuminate\Http\Request;

class UserChatPresenceStatusController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }


    public function userConnected(Request $request)
    {
        $userId = $request->user()->id; // Assuming user is authenticated
        broadcast(new UserStatusEvent($userId, 'online'))->toOthers();
        return response()->json(['status' => 'User is online.']);
    }

    // This method should be called when a user disconnects
    public function userDisconnected(Request $request)
    {
        $userId = $request->user()->id; // Assuming user is authenticated
        broadcast(new UserStatusEvent($userId, 'offline'))->toOthers();
        return response()->json(['status' => 'User is offline.']);
    }
}
