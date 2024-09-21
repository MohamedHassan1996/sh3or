<?php

namespace App\Enums\User;

enum UserRole: int{

    case VENDOR = 1;
    case CUSTOMER = 0;
    case ADMIN = 2;


    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
