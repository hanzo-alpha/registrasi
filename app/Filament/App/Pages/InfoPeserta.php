<?php

declare(strict_types=1);

namespace App\Filament\App\Pages;

use App\Enums\PaymentStatus;
use App\Enums\StatusBayar;
use App\Enums\StatusPendaftaran;
use App\Models\Pembayaran;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Infolist;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class InfoPeserta extends Page implements HasForms, HasInfolists, HasTable
{
    use InteractsWithForms;
    use InteractsWithInfolists;
    use InteractsWithTable;
    public ?Model $record = null;
    public ?array $data = [];

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $slug = 'info-peserta';
    protected static ?string $navigationLabel = 'E-Tiket';
    protected static string $view = 'filament.app.pages.info-peserta';
    protected static bool $shouldRegisterNavigation = false;
    protected ?string $heading = 'Data Pembayaran Bantaeng Trail Run 2025';

    public function mount(Pembayaran $pembayaran): void
    {
        $this->record = $pembayaran;
    }

    public function getFormStatePath(): ?string
    {
        return 'data';
    }

    public function getModel(): string
    {
        return Pembayaran::class;
    }

    public function getRecord(): ?Model
    {
        return $this->record;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Pembayaran::query()->with(['earlybird']))
            ->deferLoading()
            ->defaultSort('created_at', 'desc')
            ->queryStringIdentifier('q')
            ->columns([
                Tables\Columns\TextColumn::make('uuid_pembayaran')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('order_id')
                    ->label('Order ID')
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
                Tables\Columns\TextColumn::make('status_pendaftaran')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('status_pembayaran')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('status_transaksi')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('status_daftar')
                    ->boolean()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                Tables\Filters\SelectFilter::make('status_pembayaran')
                    ->options(StatusBayar::class)
                    ->searchable(),
                Tables\Filters\SelectFilter::make('status_transaksi')
                    ->options(PaymentStatus::class)
                    ->searchable(),
                Tables\Filters\SelectFilter::make('status_pendaftaran')
                    ->options(StatusPendaftaran::class)
                    ->searchable(),
                Tables\Filters\SelectFilter::make('kategori_lomba')
                    ->relationship('kategori', 'nama')
                    ->preload()
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // ...
            ]);
    }

    public function pembayaranInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->record)
            ->schema([
                Group::make()->schema([
                    Section::make('Data Peserta')->schema([
                        TextEntry::make('earlybird.uuid_earlybird')
                            ->label('Order ID')
                            ->color('secondary'),
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

                    Section::make('Data Alamat')->schema([
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
                    ])->columns(2),
                ])->columnSpan(2),
                Group::make()->schema([
                    Section::make('Status Peserta')->schema([
                        TextEntry::make('earlybird.ukuran_jersey')
                            ->label('Ukuran Jersey')
                            ->badge(),
                        TextEntry::make('earlybird.kategori.nama')
                            ->label('Kategori')
                            ->badge(),
                        TextEntry::make('earlybird.komunitas')
                            ->label('Komunitas')
                            ->badge(),
                        TextEntry::make('earlybird.status_earlybird')
                            ->label('Status EarlyBird')
                            ->badge(),
                    ])->columns(2),
                    Section::make('Pembayaran')->schema([
                        TextEntry::make('order_id')
                            ->label('Order ID')->badge(),
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
}
