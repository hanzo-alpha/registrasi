<?php

declare(strict_types=1);

namespace App\Filament\Admin\Widgets;

use App\Models\Pendaftaran;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestEarlybird extends BaseWidget
{
    protected static ?int $sort = 1;
    protected static ?string $heading = 'Pendaftaran Terbaru';
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Pendaftaran::query()
                    ->limit(10)
                    ->orderBy('created_at', 'desc'),
            )
            ->paginated(false)
            ->columns([
                Tables\Columns\TextColumn::make('nama_lengkap')
                    ->label('Nama Lengkap'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('kategori.nama')
                    ->label('Kategori')
                    ->badge()->alignCenter(),
                Tables\Columns\TextColumn::make('status_registrasi')
                    ->label('Status Bayar')
                    ->badge()->alignCenter(),
                Tables\Columns\TextColumn::make('status_pendaftaran')
                    ->label('Jenis Pendaftaran')
                    ->badge()->alignCenter(),
                Tables\Columns\TextColumn::make('ukuran_jersey')
                    ->label('Ukuran Jersey')
                    ->badge()->alignCenter(),

            ]);
    }
}
