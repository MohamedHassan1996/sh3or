<?php

namespace App\Enums\Otp;

enum OtpType: int{

    case REGISTER = 0;
    case RESET = 1;
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
