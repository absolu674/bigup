<?php

namespace App\Enums;

enum PaymentMethod:string
{
    case AIRTELMONEY = 'airtelmoney';
    case MOBICASH = 'mobicash';
    case VISA = 'visa';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
