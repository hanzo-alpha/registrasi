<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\EarlybirdResource\Pages;

use App\Filament\Admin\Resources\EarlybirdResource;
use App\Filament\Admin\Widgets\EarlybirdOverview;
use App\Filament\Exports\EarlybirdExporter;
use Filament\Actions;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Resources\Pages\ListRecords;

class ListEarlybirds extends ListRecords
{
    protected static string $resource = EarlybirdResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            EarlybirdOverview::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ExportAction::make()
                ->label('Ekspor Earlybird')
                ->exporter(EarlybirdExporter::class)
                ->formats([
                    ExportFormat::Xlsx,
                ])
                ->color('secondary')
                ->icon('heroicon-s-arrow-down-tray')
                ->maxRows(10000)
                ->chunkSize(250),
            Actions\CreateAction::make()
                ->icon('heroicon-o-plus'),
        ];
    }
}
