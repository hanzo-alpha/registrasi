<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources;

use App\Enums\GolonganDarah;
use App\Enums\JenisKelamin;
use App\Enums\TipeKartuIdentitas;
use App\Enums\UkuranJersey;
use App\Filament\Admin\Resources\EarlybirdResource\Pages;
use App\Models\Earlybird;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use KodePandai\Indonesia\Models\City;
use KodePandai\Indonesia\Models\District;
use KodePandai\Indonesia\Models\Province;
use Parfaitementweb\FilamentCountryField\Forms\Components\Country;

class EarlybirdResource extends Resource
{
    protected static ?string $model = Earlybird::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-plus';
    protected static ?string $label = 'Pendaftaran Earlybird';
    protected static ?string $modelLabel = 'Pendaftaran Earlybird';
    protected static ?string $pluralLabel = 'Pendaftaran Earlybird';
    protected static ?string $pluralModelLabel = 'Pendaftaran Earlybird';
    protected static ?string $navigationLabel = 'Pendaftaran Earlybird';
    protected static ?string $navigationGroup = 'Pendaftaran';

    protected static ?string $recordTitleAttribute = 'nama_lengkap';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_lengkap')
                    ->label('Nama Lengkap Peserta')
                    ->required()
                    ->autofocus()
                    ->maxLength(255),
                Forms\Components\TextInput::make('no_telp')
                    ->label('Nomor Telepon/WA Peserta')
                    ->required()
                    ->numeric()
                    ->maxLength(12),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->unique(ignoreRecord: true)
                    ->label('Email Peserta')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('tempat_lahir')
                    ->label('Tempat Lahir')
                    ->required(),
                Forms\Components\DatePicker::make('tanggal_lahir')
                    ->label('Tanggal Lahir')
                    ->required()
                    ->displayFormat('d-m-Y')
                    ->format('Y-m-d'),
                Forms\Components\Select::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->options(JenisKelamin::class)
                    ->required()
                    ->default(JenisKelamin::LAKI),
                Forms\Components\Select::make('tipe_kartu_identitas')
                    ->label('Tipe Kartu Identitas')
                    ->options(TipeKartuIdentitas::class)
                    ->required()
                    ->default(TipeKartuIdentitas::KTP),
                Forms\Components\TextInput::make('nomor_kartu_identitas')
                    ->label('Nomor Kartu Identitas')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('nama_kontak_darurat')
                    ->label('Nama Kontak Darurat')
                    ->required(),
                Forms\Components\TextInput::make('nomor_kontak_darurat')
                    ->label('Nomor Kontak Darurat')
                    ->required(),
                Forms\Components\Select::make('golongan_darah')
                    ->label('Golongan Darah')
                    ->options(GolonganDarah::class)
                    ->required(),
                Forms\Components\TextInput::make('komunitas')
                    ->label('Komunitas (Optional)')
                    ->maxLength(255),
                Forms\Components\TextInput::make('alamat')
                    ->required()
                    ->columnSpanFull(),
                Country::make('negara')
                    ->required()
                    ->searchable(),
                Forms\Components\Select::make('provinsi')
                    ->required()
                    ->options(Province::pluck('name', 'code'))
                    ->dehydrated()
                    ->live(onBlur: true)
                    ->native(false)
                    ->searchable()
                    ->afterStateUpdated(fn(Forms\Set $set) => $set('kabupaten', null)),
                Forms\Components\Select::make('kabupaten')
                    ->required()
                    ->live(onBlur: true)
                    ->options(fn(Forms\Get $get) => City::where(
                        'province_code',
                        $get('provinsi'),
                    )->pluck(
                        'name',
                        'code',
                    ))
                    ->native(false)
                    ->searchable()
                    ->afterStateUpdated(fn(Forms\Set $set) => $set('kecamatan', null)),
                Forms\Components\Select::make('kecamatan')
                    ->required()
                    ->live(onBlur: true)
                    ->options(fn(Forms\Get $get) => District::where(
                        'city_code',
                        $get('kabupaten'),
                    )->pluck(
                        'name',
                        'code',
                    ))
                    ->native(false)
                    ->searchable(),
                Forms\Components\Select::make('kategori_lomba')
                    ->label('Kategori Lomba')
                    ->relationship('kategori', 'nama')
                    ->native(false)
                    ->required(),
                Forms\Components\Select::make('ukuran_jersey')
                    ->label('Ukuran Jersey')
                    ->native(false)
                    ->options(UkuranJersey::class)
                    ->enum(UkuranJersey::class)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_lengkap')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('no_telp')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jenis_kelamin')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('tempat_lahir')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal_lahir')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('alamat')
                    ->searchable(),
                Tables\Columns\TextColumn::make('negara')
                    ->searchable(),
                Tables\Columns\TextColumn::make('prov.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kab.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kec.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tipe_kartu_identitas')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('nomor_kartu_identitas')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_kontak_darurat')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nomor_kontak_darurat')
                    ->searchable(),
                Tables\Columns\TextColumn::make('golongan_darah')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('ukuran_jersey')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('kategori_lomba')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('komunitas')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status_earlybird')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->toggleable(),
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
            'index' => Pages\ListEarlybirds::route('/'),
            'create' => Pages\CreateEarlybird::route('/create'),
            'edit' => Pages\EditEarlybird::route('/{record}/edit'),
        ];
    }
}
