<?php

namespace App\Models\Chat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'vendor_id',
        'chat_number',
    ];

    private function generateChatNumber(): string
    {
        $timestampStr = substr((string)time(), 0, 6);

        return "COM-" . generateUniqNumber(4) . $timestampStr;
    }
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->chat_number = $model->generateChatNumber();
        });

    }

}
