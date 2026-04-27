<?php

namespace App\Enum;

enum ContractEnum: string
{
    case CDD = 'CDD';
    case CDI = 'CDI';
    case Freelance = 'Freelance';

    public static function values(): array
    {
        return array_map(fn(self $e) => $e->value, self::cases());
    }
}