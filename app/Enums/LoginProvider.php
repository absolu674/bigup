<?php

namespace App\Enums;

enum LoginProvider:string
{
    case FACEBOOK = 'facebook';
    case GOOGLE = 'google';
    case BASIC = 'basic';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
