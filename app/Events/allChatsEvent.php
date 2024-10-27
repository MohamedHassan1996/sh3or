<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class allChatsEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function broadcastAs(): string
    {
        return 'all-chats'; // Event name to listen for in JS
    }

    public function broadcastWith(): array
    {

        return [
            'chatId' => $this->message['chatId'],
            'message' => $this->message['message'],
            'userId' => $this->message['userId'],
        ];
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('allChats.' . $this->message['userId']) // Broadcast to the user-specific channel
        ];
    }
}
