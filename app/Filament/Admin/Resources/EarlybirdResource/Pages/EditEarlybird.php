<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\EarlybirdResource\Pages;

use App\Filament\Admin\Resources\EarlybirdResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEarlybird extends EditRecord
{
    protected static string $resource = EarlybirdResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
