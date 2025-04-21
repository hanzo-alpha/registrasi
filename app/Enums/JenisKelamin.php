<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum JenisKelamin: string implements HasLabel, HasColor
{
    case LAKI = 'LAKI-LAKI';
    case PEREMPUAN = 'PEREMPUAN';

    public static function toArray(): array
    {
        $array = [];
        foreach (self::cases() as $case) {
            $array[$case->name] = $case->value;
        }
        return $array;
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::LAKI => 'primary',
            self::PEREMPUAN => 'secondary',
        };
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::LAKI => 'LAKI - LAKI',
            self::PEREMPUAN => 'PEREMPUAN',
        };
    }
}
