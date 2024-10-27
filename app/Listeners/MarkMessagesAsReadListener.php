<?php

namespace App\Listeners;

use App\Events\MessagesMarkedAsRead;
use App\Models\Chat\ChatMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class MarkMessagesAsReadListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MessagesMarkedAsRead $event)
    {
        // Mark all messages in the conversation as read
        ChatMessage::where('chat_id', $event->chatId)
            ->where('sender_id', '!=', $event->userId) // Ensure only recipient's messages are marked
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

            Log::info("Messages marked as read for user: {$event->userId} in conversation: {$event->chatId}");

    }
}
