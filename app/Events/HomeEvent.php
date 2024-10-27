<?php

namespace App\Events;

use Carbon\Carbon;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\Storage;

class HomeEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct($message)
    {
        $this->message = $message;

    }

    public function broadcastAs(): string
    {
        return 'home';
    }

    public function broadcastWith(): array
    {
        return [
            'chatId' => $this->message['chat_id'],
            'message' => $this->message['message'],
            'senderId' => $this->message['sender_id'],
            'senderName' => $this->message['sender_name'],
            'senderAvatar' => $this->message['sender_avatar']?Storage::url($this->message['sender_avatar']):"",
            'sendDate' => Carbon::parse($this->message['created_at'])->format('d/m/Y'),
            'sendTime'  => Carbon::parse($this->message['created_at'])->format('H:i')
        ];
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('chat.' . $this->message['chat_id'])
        ];
    }
}
