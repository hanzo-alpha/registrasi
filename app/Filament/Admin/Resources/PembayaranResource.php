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
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
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

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Group::make()->schema([
                Section::make('Data Peserta')->schema([
                    TextEntry::make('earlybird.nama_lengkap')
                        ->label('Nama Lengkap')
                        ->color('secondary'),
                    TextEntry::make('earlybird.email')
                        ->label('Email')
                        ->color('secondary'),
                    TextEntry::make('earlybird.no_telp')
                        ->label('No Telp')
                        ->color('secondary'),
                    TextEntry::make('earlybird.jenis_kelamin')
                        ->label('Jenis Kelamin')
                        ->badge(),
                    TextEntry::make('earlybird.tempat_lahir')
                        ->label('Tempat Lahir')
                        ->color('secondary'),
                    TextEntry::make('earlybird.tanggal_lahir')
                        ->label('Tanggal Lahir')
                        ->date('d M Y')
                        ->color('secondary'),
                    TextEntry::make('earlybird.alamat')
                        ->label('Alamat')
                        ->columnSpanFull()
                        ->color('secondary'),
                    TextEntry::make('earlybird.negara')
                        ->label('Negara')
                        ->color('secondary'),
                    TextEntry::make('earlybird.prov.name')
                        ->label('Provinsi')
                        ->color('secondary'),
                    TextEntry::make('earlybird.kab.name')
                        ->label('Kabupaten')
                        ->color('secondary'),
                    TextEntry::make('earlybird.kec.name')
                        ->label('Kecamatan')
                        ->color('secondary'),
                    TextEntry::make('earlybird.tipe_kartu_identitas')
                        ->badge()
                        ->label('Tipe Kartu Identitas')
                        ->color('secondary'),
                    TextEntry::make('earlybird.nomor_kartu_identitas')
                        ->label('Nomor Kartu Identitas')
                        ->color('secondary'),
                    TextEntry::make('earlybird.nama_kontak_darurat')
                        ->label('Nama Kontak Darurat')
                        ->color('secondary'),
                    TextEntry::make('earlybird.nomor_kontak_darurat')
                        ->label('Nomor Kontak Darurat')
                        ->color('secondary'),
                    TextEntry::make('earlybird.golongan_darah')
                        ->badge()
                        ->label('Golongan Darah'),
                ])->columns(2),
            ])->columnSpan(2),
            Group::make()->schema([
                Section::make('Status Peserta')->schema([

                    TextEntry::make('earlybird.ukuran_jersey')->badge(),
                    TextEntry::make('earlybird.kategori.nama')->badge(),
                    TextEntry::make('earlybird.komunitas')->badge(),
                    TextEntry::make('earlybird.status_earlybird')->badge(),
                ])->columns(2),
                Section::make('Pembayaran')->schema([
                    TextEntry::make('tipe_pembayaran')
                        ->label('Tipe Bayar')->badge(),
                    TextEntry::make('status_pembayaran')
                        ->label('Status Pembayaran')->badge(),
                    TextEntry::make('status_pendaftaran')
                        ->label('Status Pendaftaran')->badge(),
                    TextEntry::make('status_transaksi')
                        ->label('Status Transaksi')->badge(),
                ])->columns(2),
            ])->columns(1),
        ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('uuid_pembayaran')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('earlybird.nama_lengkap')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('nama_kegiatan')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('ukuran_jersey')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('kategori.nama')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('jumlah')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('satuan')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('harga_satuan')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('total_harga')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('tipe_pembayaran')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('status_pembayaran')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('status_transaksi')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('status_daftar')
                    ->boolean()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('status_pendaftaran')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('keterangan')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('lampiran')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                Tables\Actions\ViewAction::make(),
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
            'view' => Pages\ViewPembayaran::route('/{record}/view'),
        ];
    }
}
