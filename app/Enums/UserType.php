<?php

namespace App\Enums;

enum UserType:string
{
    case CLIENT = 'client';
    case ARTIST = 'artist';
    case ADMIN = 'admin';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
