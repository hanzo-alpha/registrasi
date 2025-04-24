<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources;

use App\Enums\GolonganDarah;
use App\Enums\JenisKelamin;
use App\Enums\StatusDaftar;
use App\Enums\TipeKartuIdentitas;
use App\Filament\Admin\Resources\PesertaResource\Pages;
use App\Models\Peserta;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PesertaResource extends Resource
{
    protected static ?string $model = Peserta::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $label = 'Peserta';
    protected static ?string $slug = 'peserta';
    protected static ?string $modelLabel = 'Peserta';
    protected static ?string $pluralLabel = 'Peserta';
    protected static ?string $pluralModelLabel = 'Peserta';
    protected static ?string $navigationLabel = 'Peserta';
    protected static ?string $navigationGroup = 'Pendaftaran';
    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'nama_lengkap';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(self::formPeserta());
    }

    public static function formPeserta(): array
    {
        return [
            Forms\Components\Section::make()->schema([
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
                Forms\Components\Select::make('status_peserta')
                    ->native(false)
                    ->options(StatusDaftar::class)
                    ->enum(StatusDaftar::class)
                    ->default(StatusDaftar::BELUM_TERDAFTAR)
                    ->required(),
            ])->columns(2),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('uuid_peserta')
                    ->label('ID Peserta')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('nama_lengkap')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('no_telp')
                    ->label('No. Telp')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->searchable()
                    ->badge()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('tempat_lahir')
                    ->label('Tempat Lahir')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('tanggal_lahir')
                    ->label('Tanggal Lahir')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('tipe_kartu_identitas')
                    ->label('Tipe Kartu Identitas')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('nomor_kartu_identitas')
                    ->label('Nomor Kartu Identitas')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('nama_kontak_darurat')
                    ->label('Nama Kontak Darurat')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('nomor_kontak_darurat')
                    ->label('Nomor Kontak Darurat')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('golongan_darah')
                    ->label('Golongan Darah')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('komunitas')
                    ->label('Komunitas')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('status_peserta')
                    ->label('Status Peserta')
                    ->searchable()
                    ->badge()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_peserta')
                    ->label('Status Peserta')
                    ->options(StatusDaftar::class)
                    ->native(false),
                Tables\Filters\SelectFilter::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->options(JenisKelamin::class)
                    ->native(false),
                Tables\Filters\SelectFilter::make('golongan_darah')
                    ->label('Golongan Darah')
                    ->options(GolonganDarah::class)
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\ForceDeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListPesertas::route('/'),
            'create' => Pages\CreatePeserta::route('/create'),
            'view' => Pages\ViewPeserta::route('/{record}'),
            'edit' => Pages\EditPeserta::route('/{record}/edit'),
        ];
    }
}
