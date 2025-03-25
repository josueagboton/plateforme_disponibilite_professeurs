<?php

namespace App\Enums;

enum AdminFunction: string
{
    case Secretaire = 'Secretaire';
    case DA = 'DA';
    case CS = 'CS';

    public static function values(): array
    {
        return [
            self::Secretaire->value,
            self::DA->value,
            self::CS->value,
        ];
    }
}
