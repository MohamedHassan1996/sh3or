<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'source',
        'payment_number',
        'payment_guid',
        'amount',
        'cur',
        'status',
        'description',
        'reservation_id'
    ];
}
