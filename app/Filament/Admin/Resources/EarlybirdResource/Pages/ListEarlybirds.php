<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\EarlybirdResource\Pages;

use App\Filament\Admin\Resources\EarlybirdResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEarlybirds extends ListRecords
{
    protected static string $resource = EarlybirdResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
