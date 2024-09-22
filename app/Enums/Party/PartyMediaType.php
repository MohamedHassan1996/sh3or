<?php

namespace App\Enums\Party;

enum PartyMediaType: int{

    case VEDIO = 1;
    case IMAGE = 0;

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
