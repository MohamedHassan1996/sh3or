<?php

namespace App\Http\Controllers\Api\Customer\Chat;

use App\Events\allChatsEvent;
use App\Events\HomeEvent;
use App\Events\MessageNotificationEvent;
use App\Http\Controllers\Controller;
use App\Models\Chat\Chat;
use App\Models\Chat\ChatMessage;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ChatMessagesController extends Controller
{


    public function index(Request $request)
    {

        $chatMessagesData = ChatMessage::with('sender')->where('chat_id', $request->chatId)->get();

        $chatMessages = [];

        foreach ($chatMessagesData as $key => $message) {

            $chatMessages[] = [
                'messageId' => $message->id,
                'message' => $message->message,
                'senderId' => $message->sender_id,
                'senderName' => $message->sender->name,
                'senderAvatar' => $message->sender->avatar?Storage::url($message->sender->avatar):"",
                'isRead' => $message->isRead(),
                'sendDate' => Carbon::parse($message->created_at)->format('d/m/Y'),
                'sendTime' => Carbon::parse($message->created_at)->format('H:i'),
            ];


        }

        return response()->json([
            'data' => [
                'chatMesssages' => $chatMessages,
            ]
        ]);
    }



    public function store(Request $request)
    {

        try{
            DB::beginTransaction();

            $data = $request->validate([
                'message' => 'required',
                'chatId' => 'required',
                'senderId' => 'required'
            ]);


            // Store the message in the database
            $chatMessage = ChatMessage::create([
                'chat_id' => $data['chatId'],
                'message' => $data['message'],
                'sender_id' => $data['senderId']
            ]);

            $user = User::find($data['senderId']);

            $chat = Chat::find($data['chatId']);

            $receiverId = $chat->customer_id;

            if($chat->customer_id == $data['senderId']){
                $receiverId = $chat->vendor_id;
            }


            $notification = [
                'userId' => $receiverId,
                'message' => $data['message'],
            ];


            $allChats = [
                'chatId' => $data['chatId'],
                'message' => $data['message'],
                'userId' => $receiverId,
            ];


            $chatMessageData = [
                'chat_id' => $chatMessage->chat_id,
                'message' => $chatMessage->message,
                'sender_id' => $chatMessage->sender_id,
                'sender_avatar' => $user->avatar,
                'sender_name' => $user->name,
                'created_at' => $chatMessage->created_at,
            ];

            broadcast(new HomeEvent($chatMessageData));
            broadcast(new MessageNotificationEvent($notification));

            broadcast(new allChatsEvent($allChats));


            DB::commit();

            return response()->json([
                'data' => []
            ]);



        }catch(Exception $e){

            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage()
            ], 500);

        }

    }


}
