<?php

namespace App\Enums;

enum DedicationState:string
{
    case SHIPPED = 'shipped';
    case PENDING = 'pending';
    case REJECTED = 'rejected';
    case ACCEPTED = 'accepted';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
