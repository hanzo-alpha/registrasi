<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources;

use App\Enums\PaymentStatus;
use App\Enums\StatusBayar;
use App\Enums\TipeBayar;
use App\Filament\Admin\Resources\PembayaranResource\Pages;
use App\Filament\Admin\Widgets\PembayaranOverview;
use App\Filament\Exports\PembayaranExporter;
use App\Models\Pembayaran;
use App\Models\Pendaftaran;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Form;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Number;
use Str;

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

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'nama_kegiatan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('order_id')
                            ->label('Order ID')
                            ->disabledOn(['create', 'view'])
                            ->default(Str::uuid()->toString())
                            ->maxLength(255),
                        Forms\Components\Select::make('pendaftaran_id')
                            ->label('Pendaftaran')
                            ->relationship('pendaftaran', 'no_bib')
                            ->live(onBlur: true)
                            ->native(false)
                            ->loadingMessage('Sedang dimuat...')
                            ->placeholder('Pilih salah satu pendaftaran')
                            ->searchPrompt('Cari pendaftaran berdasarkan No. BIB, Nama BIB')
                            ->preload()
                            ->optionsLimit(20)
                            ->createOptionForm(PendaftaranResource::formPendaftaran())
                            ->createOptionAction(
                                fn(Action $action) => $action->modalWidth('7xl')
                                    ->modalSubmitActionLabel('Simpan Pendaftaran')
                                    ->closeModalByEscaping(false)
                                    ->closeModalByClickingAway(),
                            )
                            ->afterStateUpdated(function (callable $set, $state, callable $get): void {
                                $pendaftaran = Pendaftaran::find($state);
                                $set('nama_kegiatan', self::buildNamaKegiatan($pendaftaran->no_bib));
                                $set('harga_satuan', $pendaftaran->kategori->harga);
                                $set('total_harga', $pendaftaran->kategori->harga * $get('jumlah'));
                            })
                            ->searchingMessage('Sedang mencari pendaftaran...')
                            ->noSearchResultsMessage('Pendaftaran tidak ditemukan.')
                            ->searchable(),
                        Forms\Components\TextInput::make('nama_kegiatan')
                            ->label('Nama Kegiatan')
                            ->readOnly()
                            ->dehydrated()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('jumlah')
                            ->numeric()
                            ->default(1),
                        Forms\Components\TextInput::make('satuan')
                            ->maxLength(255)
                            ->default('peserta'),
                        Forms\Components\TextInput::make('harga_satuan')
                            ->label('Harga Satuan')
                            ->numeric()
                            ->default(0),
                        Forms\Components\TextInput::make('total_harga')
                            ->label('Total Harga')
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
                            ->default(StatusBayar::BELUM_BAYAR)
                            ->enum(StatusBayar::class)
                            ->required(),
                        Forms\Components\Select::make('status_transaksi')
                            ->label('Status Transaksi')
                            ->native(false)
                            ->options(PaymentStatus::class)
                            ->default(PaymentStatus::PENDING)
                            ->enum(PaymentStatus::class)
                            ->required(),
                        Forms\Components\TextInput::make('keterangan')
                            ->label('Keterangan (Optional)')
                            ->maxLength(255),
                        Forms\Components\FileUpload::make('lampiran')
                            ->label('Lampiran (Optional)')
                            ->directory('pembayaran')
                            ->imageEditor()
                            ->reorderable()
                            ->openable()
                            ->image()
                            ->imageEditorAspectRatios([
                                null,
                                '16:9',
                                '4:3',
                                '1:1',
                            ])
                            ->moveFiles()
                            ->maxSize(1024)
                            ->maxFiles(1)
                            ->uploadingMessage('Sedang mengunggah...')
                            ->panelLayout('integrated'),
                    ])->columns(2),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Group::make()->schema([
                    Section::make('Detail Pembayaran')->schema([
                        TextEntry::make('order_id')
                            ->label('Order ID')->badge(),
                        TextEntry::make('tipe_pembayaran')
                            ->label('Tipe Bayar')->badge(),
                        TextEntry::make('status_pembayaran')
                            ->label('Status Pembayaran')->badge(),
                        TextEntry::make('total_harga')
                            ->label('Total Bayar')
                            ->formatStateUsing(fn($state) => Number::currency($state, 'IDR', 'id', 0))
                            ->color('primary'),
                        TextEntry::make('status_transaksi')
                            ->label('Status Transaksi')->badge(),
                    ])->columns(2),
                    Section::make('Detail Pendaftaran')->schema([
                        TextEntry::make('pendaftaran.uuid_pendaftaran')
                            ->label('ID Pendaftaran')
                            ->color('secondary'),
                        TextEntry::make('pendaftaran.no_bib')
                            ->label('No. BIB Peserta')
                            ->color('secondary'),
                        TextEntry::make('pendaftaran.nama_bib')
                            ->label('Nama BIB Peserta')
                            ->color('secondary'),
                    ])->columns(2),

                ])->columnSpan(2),
                Group::make()->schema([
                    Section::make('Detail Peserta')->schema([
                        TextEntry::make('pendaftaran.ukuran_jersey')
                            ->label('Ukuran Jersey')
                            ->badge(),
                        TextEntry::make('pendaftaran.kategori.nama')
                            ->label('Kategori')
                            ->badge(),
                        TextEntry::make('pendaftaran.peserta.komunitas')
                            ->label('Komunitas')
                            ->badge(),
                        TextEntry::make('pendaftaran.status_pendaftaran')
                            ->label('Status Pendaftaran')
                            ->badge(),
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
                Tables\Columns\TextColumn::make('uuid_pembayaran')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('order_id')
                    ->label('Order ID')
                    ->searchable()
                    ->copyable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('pendaftaran.peserta.nama_lengkap')
                    ->label('Nama Peserta')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('pendaftaran.peserta.nomor_kartu_identitas')
                    ->label('Nomor Identitas Peserta')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('nama_kegiatan')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('pendaftaran.kategori.nama')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('tipe_pembayaran')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('status_pembayaran')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('status_transaksi')
                    ->searchable()
                    ->sortable()
                    ->badge()
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
                Tables\Filters\SelectFilter::make('status_pembayaran')
                    ->options(StatusBayar::class)
                    ->searchable(),
                Tables\Filters\SelectFilter::make('status_transaksi')
                    ->options(PaymentStatus::class)
                    ->searchable(),
                Tables\Filters\SelectFilter::make('tipe_pembayaran')
                    ->options(TipeBayar::class)
                    ->searchable(),
                Tables\Filters\TrashedFilter::make(),
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
                    Tables\Actions\ExportBulkAction::make()
                        ->exporter(PembayaranExporter::class),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [

        ];
    }

    public static function getWidgets(): array
    {
        return [
            PembayaranOverview::class,
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
            'index' => Pages\ListPembayarans::route('/'),
            'create' => Pages\CreatePembayaran::route('/create'),
            'edit' => Pages\EditPembayaran::route('/{record}/edit'),
            'view' => Pages\ViewPembayaran::route('/{record}/view'),
        ];
    }

    private static function buildNamaKegiatan($kategori): string
    {
        return "Pendaftaran {$kategori} Bantaeng Trail Run 2025";
    }
}
