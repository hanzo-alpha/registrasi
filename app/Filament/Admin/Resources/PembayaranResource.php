<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources;

use App\Enums\PaymentStatus;
use App\Enums\StatusBayar;
use App\Enums\StatusDaftar;
use App\Enums\StatusPendaftaran;
use App\Enums\TipeBayar;
use App\Enums\UkuranJersey;
use App\Filament\Admin\Resources\PembayaranResource\Pages;
use App\Models\Pembayaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PembayaranResource extends Resource
{
    protected static ?string $model = Pembayaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $label = 'Pembayaran';
    protected static ?string $modelLabel = 'Pembayaran';
    protected static ?string $pluralLabel = 'Pembayaran';
    protected static ?string $pluralModelLabel = 'Pembayaran';
    protected static ?string $navigationLabel = 'Pembayaran';
    protected static ?string $navigationGroup = 'Pendaftaran';

    protected static ?string $recordTitleAttribute = 'nama_kegiatan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('registrasi_id')
                    ->relationship('registrasi', 'id')
                    ->native(false)
                    ->preload()
                    ->required()
                    ->optionsLimit(30),
                Forms\Components\TextInput::make('nama_kegiatan')
                    ->maxLength(255),
                Forms\Components\Select::make('ukuran_jersey')
                    ->label('Ukuran Jersey')
                    ->native(false)
                    ->options(UkuranJersey::class)
                    ->enum(UkuranJersey::class)
                    ->required(),
                Forms\Components\Select::make('kategori_lomba')
                    ->label('Kategori Lomba')
                    ->relationship('kategori', 'nama')
                    ->native(false)
                    ->required(),
                Forms\Components\TextInput::make('jumlah')
                    ->numeric()
                    ->default(1),
                Forms\Components\TextInput::make('satuan')
                    ->maxLength(255)
                    ->default('peserta'),
                Forms\Components\TextInput::make('harga_satuan')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('total_harga')
                    ->numeric()
                    ->default(0),
                Forms\Components\Select::make('tipe_pembayaran')
                    ->label('Tipe Pembayaran')
                    ->native(false)
                    ->options(TipeBayar::class)
                    ->enum(TipeBayar::class)
                    ->required(),
                Forms\Components\Select::make('status_pembayaran')
                    ->label('Status Pembayaran')
                    ->native(false)
                    ->options(StatusBayar::class)
                    ->enum(StatusBayar::class)
                    ->required(),
                Forms\Components\Select::make('status_transaksi')
                    ->label('Status Transaksi')
                    ->native(false)
                    ->options(PaymentStatus::class)
                    ->enum(PaymentStatus::class)
                    ->required(),
                Forms\Components\Select::make('status_daftar')
                    ->label('Status Daftar')
                    ->native(false)
                    ->options(StatusDaftar::class)
                    ->enum(StatusDaftar::class)
                    ->required(),
                Forms\Components\Select::make('status_pendaftaran')
                    ->label('Status Pendaftaran')
                    ->native(false)
                    ->options(StatusPendaftaran::class)
                    ->enum(StatusPendaftaran::class)
                    ->required(),
                Forms\Components\TextInput::make('keterangan')
                    ->maxLength(255),
                Forms\Components\FileUpload::make('lampiran'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('uuid_pembayaran')
                    ->searchable(),
                Tables\Columns\TextColumn::make('registrasi.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_kegiatan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ukuran_jersey')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kategori_lomba')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jumlah')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('satuan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('harga_satuan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_harga')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tipe_pembayaran')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status_pembayaran')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status_transaksi')
                    ->searchable(),
                Tables\Columns\IconColumn::make('status_daftar')
                    ->boolean(),
                Tables\Columns\TextColumn::make('status_pendaftaran')
                    ->searchable(),
                Tables\Columns\TextColumn::make('keterangan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('lampiran')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPembayarans::route('/'),
            'create' => Pages\CreatePembayaran::route('/create'),
            'edit' => Pages\EditPembayaran::route('/{record}/edit'),
        ];
    }
}
