<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageNotificationEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct($message)
    {
        $this->message = $message;

    }

    public function broadcastAs(): string
    {
        return 'chat-notification';
    }

    public function broadcastWith(): array
    {
        return [
            'message' => $this->message['message'],
            'userId' => $this->message['userId'],
        ];
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('chatNotification.' . $this->message['userId'])
        ];
    }
}
