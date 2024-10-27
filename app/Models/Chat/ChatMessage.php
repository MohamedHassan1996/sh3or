<?php

namespace App\Models\Chat;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'sender_id',
        'message',
        'read_at'
    ];
    public function isRead()
    {
        return !is_null($this->read_at);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

}
