<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\PendaftaranResource\Pages;

use App\Filament\Admin\Resources\PendaftaranResource;
use App\Filament\Admin\Widgets\PendaftaranOverview;
use App\Filament\Exports\PendaftaranExporter;
use Filament\Actions;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Resources\Pages\ListRecords;

class ListPendaftarans extends ListRecords
{
    protected static string $resource = PendaftaranResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            PendaftaranOverview::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ExportAction::make()
                ->label('Ekspor Excel')
                ->exporter(PendaftaranExporter::class)
                ->formats([
                    ExportFormat::Xlsx,
                ])
                ->color('success')
                ->icon('heroicon-s-arrow-down-tray')
                ->maxRows(10000)
                ->chunkSize(250),
            Actions\CreateAction::make()
                ->icon('heroicon-o-plus'),
        ];
    }
}
