<?php

namespace App\Enums\Party;

enum PartyCancelStatus: int{

    case NON_CANCELLABLE = 0;

    case CANCELLABLE = 1;

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
