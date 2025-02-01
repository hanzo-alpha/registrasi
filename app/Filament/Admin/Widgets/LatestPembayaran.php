<?php

declare(strict_types=1);

namespace App\Filament\Admin\Widgets;

use App\Models\Pembayaran;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestPembayaran extends BaseWidget
{
    protected static ?int $sort = 1;
    protected static ?string $heading = 'Pembayaran Terbaru';
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Pembayaran::query()
                    ->orderBy('created_at', 'desc'),
            )
            ->columns([
                Tables\Columns\TextColumn::make('earlybird.nama_lengkap'),
                Tables\Columns\TextColumn::make('kategori.nama')
                    ->alignCenter()
                    ->badge(),
                Tables\Columns\TextColumn::make('ukuran_jersey')
                    ->badge()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('total_harga')
                    ->money(currency: 'IDR', locale: 'id'),
                Tables\Columns\TextColumn::make('status_pembayaran')
                    ->badge()
                    ->alignCenter(),
            ]);
    }
}
