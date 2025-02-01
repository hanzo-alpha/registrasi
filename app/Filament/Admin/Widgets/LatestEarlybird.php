<?php

declare(strict_types=1);

namespace App\Filament\Admin\Widgets;

use App\Models\Earlybird;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestEarlybird extends BaseWidget
{
    protected static ?int $sort = 2;
    protected static ?string $heading = 'Pendaftaran Earlybird Terbaru';
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Earlybird::query()
                    ->orderBy('created_at', 'desc'),
            )
            ->columns([
                Tables\Columns\TextColumn::make('nama_lengkap'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('no_telp'),
                Tables\Columns\TextColumn::make('ukuran_jersey')->badge()->alignCenter(),
                Tables\Columns\TextColumn::make('kategori.nama')->badge()->alignCenter(),
            ]);
    }
}
