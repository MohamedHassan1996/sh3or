<?php

namespace App\Http\Controllers\Api\Customer\Chat;

use App\Http\Controllers\Controller;
use App\Models\Chat\Chat;
use App\Models\Chat\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{


    public function index(Request $request)
    {
        $chatsData = Chat::where('customer_id', $request->userId)->orWhere('vendor_id', $request->userId)->get();

        $chats = [];

        foreach ($chatsData as $key => $chatData) {
            $chatMessage = ChatMessage::where('chat_id', $chatData->id)->latest()->first();

            if($request->userId == $chatData->vendor_id){
                $user = User::find($chatData->customer_id);
            }else{
                $user = User::find($chatData->vendor_id);
            }

            $unreadCount = $chatData->unreadMessagesCount($request->userId); // Get unread messages count

            $chats[] = [
                'chatId' => $chatData->id,
                'message' => $chatMessage->message,
                'name' => $user->name,
                'unreadCount' => $unreadCount,
                'avatar' => $user->avatar ? Storage::url($user->avatar) : "",
            ];


        }

        return response()->json([
            'data' => [
                'chats' => $chats,
            ]
        ]);
    }

}
