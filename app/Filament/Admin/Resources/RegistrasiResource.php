<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources;

use App\Enums\GolonganDarah;
use App\Enums\JenisKelamin;
use App\Enums\StatusRegistrasi;
use App\Enums\TipeKartuIdentitas;
use App\Enums\UkuranJersey;
use App\Filament\Admin\Resources\RegistrasiResource\Pages;
use App\Filament\Admin\Widgets\PendaftaranOverview;
use App\Models\Earlybird;
use App\Models\Registrasi;
use App\Services\MidtransAPI;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
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
                        ->required()
                        ->native(false)
                        ->options(UkuranJersey::class)
                        ->enum(UkuranJersey::class)
                        ->searchable(),
                    Forms\Components\Select::make('status_registrasi')
                        ->native(false)
                        ->options(StatusRegistrasi::class)
                        ->enum(StatusRegistrasi::class)
                        ->required(),
                ])->columns(2),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Group::make()->schema([
                Section::make('Data Peserta')->schema([
                    TextEntry::make('uuid_registrasi')
                        ->label('UUID')
                        ->color('secondary'),
                    TextEntry::make('nama_lengkap')
                        ->label('Nama Lengkap')
                        ->color('secondary'),
                    TextEntry::make('email')
                        ->label('Email')
                        ->color('secondary'),
                    TextEntry::make('no_telp')
                        ->label('No Telp')
                        ->color('secondary'),
                    TextEntry::make('jenis_kelamin')
                        ->label('Jenis Kelamin')
                        ->badge(),
                    TextEntry::make('tempat_lahir')
                        ->label('Tempat Lahir')
                        ->color('secondary'),
                    TextEntry::make('tanggal_lahir')
                        ->label('Tanggal Lahir')
                        ->date('d M Y')
                        ->color('secondary'),
                    TextEntry::make('tipe_kartu_identitas')
                        ->label('Tipe Kartu Identitas')
                        ->badge(),
                    TextEntry::make('nomor_kartu_identitas')
                        ->label('Nomor Kartu Identitas')
                        ->color('secondary'),
                    TextEntry::make('nama_kontak_darurat')
                        ->label('Nama Kontak Darurat')
                        ->color('secondary'),
                    TextEntry::make('nomor_kontak_darurat')
                        ->label('Nomor Kontak Darurat')
                        ->color('secondary'),
                    TextEntry::make('golongan_darah')->badge()
                        ->label('Golongan Darah'),
                ])->columns(2),
                Section::make('Data Alamat')->schema([
                    TextEntry::make('alamat')
                        ->label('Alamat')
                        ->columnSpanFull()
                        ->color('secondary'),
                    TextEntry::make('negara')
                        ->label('Negara')
                        ->color('secondary'),
                    TextEntry::make('prov.name')
                        ->label('Provinsi')
                        ->color('secondary'),
                    TextEntry::make('kab.name')
                        ->label('Kabupaten')
                        ->color('secondary'),
                    TextEntry::make('kec.name')
                        ->label('Kecamatan')
                        ->color('secondary'),
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
            ->poll('5s')
            ->deferLoading()
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
                    ->sortable()
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
                Tables\Columns\TextColumn::make('prov.name')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('kab.name')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('kec.name')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('tipe_kartu_identitas')
                    ->searchable()
                    ->sortable()
                    ->badge()
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
                    ->sortable()
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('ukuran_jersey')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->alignCenter()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('kategori.nama')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->alignCenter()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('komunitas')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('status_registrasi')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->alignCenter()
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
                Tables\Filters\SelectFilter::make('status_registrasi')
                    ->options(StatusRegistrasi::class)
                    ->searchable(),
                Tables\Filters\SelectFilter::make('jenis_kelamin')
                    ->options(JenisKelamin::class)
                    ->searchable(),
                Tables\Filters\SelectFilter::make('kategori_lomba')
                    ->relationship('kategori', 'nama')
                    ->preload()
                    ->searchable(),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('Check Pembayaran')
                    ->label('Check Pembayaran')
                    ->icon('heroicon-o-check')
                    ->color('yellow')
                    ->action(function ($record): void {
                        self::checkPembayaran($record);
                    }),
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
                    Tables\Actions\DeleteBulkAction::make()
                        ->action(function (Collection $records): void {
                            $records->each(function ($record): void {
                                $record->delete();
                                $record->pembayaran->delete();
                            });
                        }),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\BulkAction::make('check_pembayaran')
                        ->label('Check Pembayaran')
                        ->icon('heroicon-s-check')
                        ->color(Color::Yellow)
                        ->action(function (Collection $records): void {
                            $records->each(function (Model $record): void {
                                self::checkPembayaran($record);
                            });
                        }),
                ]),
            ]);
    }

    public static function getWidgets(): array
    {
        return [
            PendaftaranOverview::class,
        ];
    }

    public static function getRelations(): array
    {
        return [

        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->orderByDesc('created_at')
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
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

    /**
     * @param  \Illuminate\Database\Eloquent\Model|\App\Models\Earlybird  $record
     * @return void
     * @throws \Illuminate\Http\Client\ConnectionException
     */
    private static function checkPembayaran(Model|Earlybird $record): void
    {
        $orderId = $record->pembayaran?->order_id;
        $data = MidtransAPI::getStatusMessage($orderId);

        if (null === $data['status_message']) {
            Notification::make()
                ->danger()
                ->title('Status Transaksi')
                ->body('Transaksi tidak ditemukan')
                ->send();
            return;
        }

        $notif = Notification::make('statuspembayaran');
        match ($data['status']) {
            'success' => $notif->success(),
            'warning' => $notif->warning(),
            'info' => $notif->info(),
            'danger' => $notif->danger(),
        };

        $notif->title('Notifikasi Pembayaran')
            ->body($data['status_message'])
            ->send()
            ->sendToDatabase(auth()->user());
    }
}
