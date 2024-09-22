<?php

namespace App\Enums\Party\Reservation;

enum ReservationStatus: int{

    case RESERVED = 1;
    case CANCELLED = 2;

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
