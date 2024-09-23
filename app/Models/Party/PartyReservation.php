<?php

namespace App\Models\Party;

use App\Enums\Party\Reservation\PayType;
use App\Enums\Party\Reservation\ReservationStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartyReservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'party_id',
        'reservation_number',
        'date',
        'start_prep',
        'end_prep',
        'price',
        'price_after_discount',
        'status',
        'pay_type',
        'vendor_id',
        'customer_id',
    ];

    protected $casts = [
        // 'date' => 'date',
        // 'start_prep' => 'time',
        // 'end_prep' => 'time',
        'status' => ReservationStatus::class,
        'pay_type' => PayType::class,
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {

            $model->reservation_number = 'R' . generateUniqNumber(4) . "-" . Carbon::now()->format("m/Y");

        });
    }
}
