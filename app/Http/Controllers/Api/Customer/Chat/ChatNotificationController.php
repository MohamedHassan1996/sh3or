<?php

namespace App\Http\Controllers\Api\Customer\Chat;

use App\Http\Controllers\Controller;
use App\Models\Chat\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatNotificationController extends Controller
{


    public function show(Request $request)
    {
        $userId = $request->userId;

        // Get the total count of unread messages across all chats for this user
        $unreadCount = ChatMessage::whereNull('read_at')
            ->where(function($query) use ($userId) {
                $query->where('chat_id', function($subQuery) use ($userId) {
                    $subQuery->select('id')
                        ->from('chats')
                        ->where('customer_id', $userId)
                        ->orWhere('vendor_id', $userId);
                });
            })
            ->where('sender_id', '!=', $userId) // Exclude messages sent by the user
            ->count();

        return response()->json([
            'unreadCount' => $unreadCount,
        ]);
    }


    public function update(Request $request)
    {
        $userId = $request->userId;
        $chatId = $request->chatId;

        ChatMessage::where('chat_id', $chatId)
            ->where('sender_id', '!=', $userId) // Exclude messages sent by the user
            ->update(['read_at' => now()]);

        return response()->json([
            'data' => [
                'read' => 1,
            ],
        ]);
    }



}
