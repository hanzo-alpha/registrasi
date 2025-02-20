<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\RegistrasiResource\Pages;

use App\Filament\Admin\Resources\RegistrasiResource;
use App\Filament\Admin\Widgets\PendaftaranOverview;
use App\Filament\Exports\RegistrasiExporter;
use Filament\Actions;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Resources\Pages\ListRecords;

class ListRegistrasis extends ListRecords
{
    protected static string $resource = RegistrasiResource::class;

    //    protected function getHeaderWidgets(): array
    //    {
    //        return [
    //            PendaftaranOverview::class,
    //        ];
    //    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ExportAction::make()
                ->label('Ekspor')
                ->exporter(RegistrasiExporter::class)
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
