<?php

namespace App\Enums\Party\Reservation;

enum PayType: int{

    case CASH = 1;
    case CARD = 2;

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
