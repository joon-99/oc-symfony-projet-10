<?php

namespace App\Enum;

enum TaskCategoryEnum: string
{
    case TODO = 'To Do';
    case DOING = 'Doing';
    case DONE = 'Done';

    public static function values(): array
    {
        return array_map(fn(self $e) => $e->value, self::cases());
    }
}