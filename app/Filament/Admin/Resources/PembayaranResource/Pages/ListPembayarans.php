<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\PembayaranResource\Pages;

use App\Filament\Admin\Resources\PembayaranResource;
use App\Filament\Exports\PembayaranExporter;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ListRecords;

class ListPembayarans extends ListRecords
{
    protected static string $resource = PembayaranResource::class;
    //    public $defaultAction = 'onboarding';
    //
    //    public function onboardingAction(): Action
    //    {
    //        return Action::make('onboarding')
    //            ->modalHeading('Welcome')
    //            ->form([
    //                Select::make('registrasi_id')
    //                    ->label('Registrasi')
    //                    ->relationship('earlybird', 'nama_lengkap')
    //                    ->native(false)
    //                    ->preload()
    //                    ->required()
    //                    ->optionsLimit(30),
    //            ])
    //            ->action(function (array $data): void {
    //                dd($data);
    //            });
    //    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ExportAction::make()
                ->label('Ekspor Data Pembayaran')
                ->exporter(PembayaranExporter::class)
                ->formats([
                    ExportFormat::Xlsx,
                ])
                ->color('secondary')
                ->icon('heroicon-s-arrow-down-tray')
                ->maxRows(10000)
                ->chunkSize(250),
            Actions\CreateAction::make()
                ->icon('heroicon-s-plus'),
        ];
    }
}
