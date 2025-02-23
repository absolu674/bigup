<?php

namespace App\Enums;

enum TransactionStatus:string
{
    case SUCCESS = 'success';
    case FAIL = 'fail';
    case PENDING = 'pending';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
