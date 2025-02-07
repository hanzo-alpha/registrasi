<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\RegistrasiResource\Pages;

use App\Filament\Admin\Resources\RegistrasiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class ViewRegistrasi extends EditRecord
{
    protected static string $resource = RegistrasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
