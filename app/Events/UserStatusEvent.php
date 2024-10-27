<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UserStatusEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $status;

    public function __construct($userId, $status)
    {
        $this->userId = $userId; // User ID whose status is changing
        $this->status = $status; // Online or Offline
    }
    public function broadcastAs(): string
    {
        return 'user-status';
    }

    public function broadcastWith()
    {
        return [
            'userId' => $this->userId,
            'status' => $this->status,
        ];
    }

    public function broadcastOn():array
    {
        return [
            new PrivateChannel('user.' . $this->userId)
        ];
    }
}
