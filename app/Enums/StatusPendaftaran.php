<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasDescription;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum StatusPendaftaran: string implements HasColor, HasDescription, HasIcon, HasLabel
{
    case EARLYBIRD = 'early_bird';
    case NORMAL = 'normal';

    public function getDescription(): ?string
    {
        return match ($this) {
            self::EARLYBIRD => 'Pendaftaran Early Bird',
            self::NORMAL => 'Pendaftaran Normal',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::EARLYBIRD => 'heroicon-o-check-circle',
            self::NORMAL => 'heroicon-o-minus-circle',
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::EARLYBIRD => 'primary',
            self::NORMAL => 'secondary',
        };
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::EARLYBIRD => 'Early Bird',
            self::NORMAL => 'Normal',
        };
    }
}
