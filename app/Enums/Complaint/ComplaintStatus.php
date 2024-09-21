<?php

namespace App\Enums\Complaint;

enum ComplaintStatus: int{

    case CLOSED = 1;
    case IN_PROGRESS = 0;

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
