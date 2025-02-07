<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources;

use App\Enums\GolonganDarah;
use App\Enums\JenisKelamin;
use App\Enums\KategoriLomba;
use App\Enums\StatusRegistrasi;
use App\Enums\TipeKartuIdentitas;
use App\Enums\UkuranJersey;
use App\Filament\Admin\Resources\RegistrasiResource\Pages;
use App\Models\Registrasi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use KodePandai\Indonesia\Models\City;
use KodePandai\Indonesia\Models\District;
use KodePandai\Indonesia\Models\Province;
use Parfaitementweb\FilamentCountryField\Forms\Components\Country;

class RegistrasiResource extends Resource
{
    protected static ?string $model = Registrasi::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-plus';
    protected static ?string $label = 'Pendaftaran Normal';
    protected static ?string $modelLabel = 'Pendaftaran Normal';
    protected static ?string $pluralLabel = 'Pendaftaran Normal';
    protected static ?string $pluralModelLabel = 'Pendaftaran Normal';
    protected static ?string $navigationLabel = 'Pendaftaran Normal';
    protected static ?string $navigationGroup = 'Pendaftaran';

    protected static ?string $recordTitleAttribute = 'nama_lengkap';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_lengkap')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('no_telp')
                    ->tel()
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('jenis_kelamin')
                    ->required()
                    ->options(JenisKelamin::class),
                Forms\Components\TextInput::make('tempat_lahir')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('tanggal_lahir')
                    ->required(),
                Forms\Components\TextInput::make('alamat')
                    ->required()
                    ->maxLength(255),
                Country::make('negara')
                    ->required(),
                Forms\Components\Select::make('provinsi')
                    ->required()
                    ->options(Province::pluck('name', 'code'))
                    ->searchable(),
                Forms\Components\Select::make('kabupaten')
                    ->required()
                    ->options(City::pluck('name', 'code'))
                    ->searchable(),
                Forms\Components\Select::make('kecamatan')
                    ->required()
                    ->options(District::pluck('name', 'code'))
                    ->searchable(),
                Forms\Components\Select::make('tipe_kartu_identitas')
                    ->required()
                    ->options(TipeKartuIdentitas::class),
                Forms\Components\TextInput::make('nomor_kartu_identitas')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nama_kontak_darurat')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nomor_kontak_darurat')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('golongan_darah')
                    ->required()
                    ->options(GolonganDarah::class)
                    ->searchable(),
                Forms\Components\TextInput::make('kewarganegaraan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('ukuran_jersey')
                    ->required()
                    ->options(UkuranJersey::class)
                    ->searchable(),
                Forms\Components\TextInput::make('komunitas')
                    ->maxLength(255),
                Forms\Components\TextInput::make('jumlah_peserta')
                    ->required()
                    ->numeric()
                    ->default(1),
                Forms\Components\Select::make('kategori_lomba')
                    ->required()
                    ->options(KategoriLomba::class)
                    ->searchable(),
                //                Forms\Components\Select::make('status_registrasi')
                //                    ->options(StatusRegistrasi::class),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Group::make()->schema([
                Section::make('Data Peserta')->schema([
                    TextEntry::make('uuid_registrasi')
                        ->label('UUID'),
                    TextEntry::make('nama_lengkap'),
                    TextEntry::make('email'),
                    TextEntry::make('no_telp'),
                    TextEntry::make('jenis_kelamin')
                        ->badge(),
                    TextEntry::make('tempat_lahir'),
                    TextEntry::make('tanggal_lahir')->date('d M Y'),
                    TextEntry::make('alamat')->columnSpanFull(),
                    TextEntry::make('negara'),
                    TextEntry::make('prov.name'),
                    TextEntry::make('kab.name'),
                    TextEntry::make('kec.name'),
                    TextEntry::make('tipe_kartu_identitas')->badge(),
                    TextEntry::make('nomor_kartu_identitas'),
                    TextEntry::make('nama_kontak_darurat'),
                    TextEntry::make('nomor_kontak_darurat'),
                    TextEntry::make('golongan_darah')->badge(),
                ])->columns(2),
            ])->columnSpan(2),
            Group::make()->schema([
                Section::make('Status Peserta')->schema([

                    TextEntry::make('ukuran_jersey')->badge(),
                    TextEntry::make('kategori.nama')->badge(),
                    TextEntry::make('komunitas')->badge(),
                    TextEntry::make('status_registrasi')->badge(),
                ])->columns(2),
                Section::make('Pembayaran')->schema([
                    TextEntry::make('pembayaran.tipe_pembayaran')
                        ->label('Tipe Bayar')->badge(),
                    TextEntry::make('pembayaran.status_pembayaran')
                        ->label('Status Pembayaran')->badge(),
                    TextEntry::make('pembayaran.status_pendaftaran')
                        ->label('Status Pendaftaran')->badge(),
                    TextEntry::make('pembayaran.status_transaksi')
                        ->label('Status Transaksi')->badge(),
                ])->columns(2),
            ])->columns(1),
        ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('uuid_registrasi')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('nama_lengkap')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('no_telp')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('jenis_kelamin')
                    ->searchable()
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('tempat_lahir')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('tanggal_lahir')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('alamat')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('negara')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('provinsi')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('kabupaten')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('kecamatan')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('tipe_kartu_identitas')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('nomor_kartu_identitas')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('nama_kontak_darurat')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('nomor_kontak_darurat')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('golongan_darah')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('kewarganegaraan')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('ukuran_jersey')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('jumlah_peserta')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('komunitas')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kategori_lomba')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status_registrasi')
                    ->searchable(),
                //                Tables\Columns\TextColumn::make('created_at')
                //                    ->dateTime()
                //                    ->sortable()
                //                    ->toggleable(isToggledHiddenByDefault: true),
                //                Tables\Columns\TextColumn::make('updated_at')
                //                    ->dateTime()
                //                    ->sortable()
                //                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListRegistrasis::route('/'),
            'create' => Pages\CreateRegistrasi::route('/create'),
            'edit' => Pages\EditRegistrasi::route('/{record}/edit'),
            'view' => Pages\ViewRegistrasi::route('/{record}/view'),
        ];
    }
}
