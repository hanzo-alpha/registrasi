<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources;

use App\Enums\GolonganDarah;
use App\Enums\JenisKelamin;
use App\Enums\PaymentStatus;
use App\Enums\StatusBayar;
use App\Enums\StatusRegistrasi;
use App\Enums\TipeKartuIdentitas;
use App\Enums\UkuranJersey;
use App\Filament\Admin\Resources\EarlybirdResource\Pages;
use App\Models\Earlybird;
use App\Services\MidtransAPI;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
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
                        ->label('Ukuran Jersey')
                        ->native(false)
                        ->options(UkuranJersey::class)
                        ->enum(UkuranJersey::class)
                        ->required(),
                    Forms\Components\Select::make('status_earlybird')
                        ->label('Status Earlybird')
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
                    TextEntry::make('uuid_earlybird')
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
                    TextEntry::make('status_earlybird')->badge(),
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
                Tables\Columns\TextColumn::make('uuid_earlybird')
                    ->searchable()
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
                Tables\Columns\TextColumn::make('status_earlybird')
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
                Tables\Filters\SelectFilter::make('status_earlybird')
                    ->options(StatusRegistrasi::class)
                    ->searchable(),
                Tables\Filters\SelectFilter::make('jenis_kelamin')
                    ->options(JenisKelamin::class)
                    ->searchable(),
                Tables\Filters\SelectFilter::make('kategori_lomba')
                    ->relationship('kategori', 'nama')
                    ->preload()
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\Action::make('Check Pembayaran')
                    ->label('Check Pembayaran')
                    ->icon('heroicon-o-check')
                    ->color('yellow')
                    ->action(function ($record): void {
                        $orderId = $record->pembayaran?->order_id;
                        $data = MidtransAPI::getTransactionStatus($orderId);

                        if (blank($data) || 0 === count($data)) {
                            Notification::make('Info')
                                ->danger()
                                ->title('Data sudah terupdate')
                                ->send();
                            return;
                        }

                        $detail = $data['sukses'] ? $data['responses'] : [];

                        if (empty($detail) || count($detail) < 1) {
                            Notification::make()
                                ->info()
                                ->title('Data tidak ditemukan')
                                ->send();
                            return;
                        }

                        if (isset($detail['transaction_status']) && 'expire' === $detail['transaction_status']) {
                            $record->delete();
                            $record->pembayaran->delete();
                            Notification::make('Info')
                                ->danger()
                                ->title($detail['status_message'])
                                ->send();
                            return;
                        }

                        if (isset($detail['transaction_status']) && 'settlement' === $detail['transaction_status']) {
                            Notification::make()
                                ->success()
                                ->title('Transaksi sudah berhasil terbayar')
                                ->send();
                            return;
                        }

                        $update = $record->pembayaran;

                        $status = match ($detail['transaction_status']) {
                            PaymentStatus::SETTLEMENT->value,
                            PaymentStatus::CAPTURE->value => StatusBayar::SUDAH_BAYAR,
                            PaymentStatus::FAILURE->value,
                            PaymentStatus::CANCEL->value,
                            PaymentStatus::DENY->value,
                            PaymentStatus::EXPIRE->value => StatusBayar::GAGAL,
                            PaymentStatus::PENDING->value => StatusBayar::PENDING,
                            default => StatusBayar::BELUM_BAYAR,
                        };

                        $statusRegistrasi = match ($detail['transaction_status']) {
                            PaymentStatus::SETTLEMENT->value,
                            PaymentStatus::CAPTURE->value => StatusRegistrasi::BERHASIL,
                            PaymentStatus::FAILURE->value,
                            PaymentStatus::CANCEL->value,
                            PaymentStatus::DENY->value,
                            PaymentStatus::EXPIRE->value => StatusRegistrasi::BATAL,
                            PaymentStatus::PENDING->value => StatusRegistrasi::TUNDA,
                            PaymentStatus::AUTHORIZE->value => StatusRegistrasi::PROSES,
                            PaymentStatus::CHARGEBACK->value,
                            PaymentStatus::PARTIAL_REFUND->value,
                            PaymentStatus::REFUND->value,
                            PaymentStatus::PARTIAL_CHARGEBACK->value => StatusRegistrasi::PENGEMBALIAN,
                        };

                        $update->status_transaksi = $detail['transaction_status'];
                        $update->status_pembayaran = $status;
                        $update->detail_transaksi = $detail;

                        $record->update([
                            'status_earlybird' => $statusRegistrasi,
                        ]);

                        $update->save();
                        Notification::make('sukses')
                            ->success()
                            ->title('Berhasil mengupdate pembayaran')
                            ->send()
                            ->sendToDatabase(auth()->user());
                    }),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
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
            'view' => Pages\ViewEarlybirds::route('/{record}/view'),
        ];
    }
}
