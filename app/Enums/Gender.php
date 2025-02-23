<?php

namespace App\Enums;

enum Gender:string
{
    case WOMAN = 'woman';
    case MAN = 'man';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
