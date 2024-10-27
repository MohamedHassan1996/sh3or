<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessagesMarkedAsRead
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $chatId;
    public $userId;

    /**
     * Create a new event instance.
     */
    public function __construct($chatId, $userId)
    {
        $this->chatId = $chatId;
        $this->userId = $userId;

    }

    public function broadcastAs()
    {
        return 'messages-marked-as-read';
    }
    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn():array
    {
        return [
            new PrivateChannel('chat.1' /*. $this->message->chat_id*/)
        ];
    }

}
