<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum KategoriLomba: string implements HasLabel, HasIcon, HasColor
{
    case DELAPAN_K = '8K';
    case EARLY_DELAPAN_K = 'early_8K';
    case LIMABELAS_K = '15K';
    case EARLY_LIMABELAS_K = 'early_15K';

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
            self::DELAPAN_K => 'primary',
            self::EARLY_DELAPAN_K => 'success',
            self::LIMABELAS_K => 'secondary',
            self::EARLY_LIMABELAS_K => 'info',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::DELAPAN_K, self::EARLY_DELAPAN_K, self::LIMABELAS_K, self::EARLY_LIMABELAS_K => 'heroicon-o-map-pin',
        };
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::DELAPAN_K => '8K - IDR 250K',
            self::EARLY_DELAPAN_K => 'EARLY 8K - IDR 200K',
            self::LIMABELAS_K => '15K - IDR 275K',
            self::EARLY_LIMABELAS_K => 'EARLY 15K - IDR 350K',
        };
    }
}
