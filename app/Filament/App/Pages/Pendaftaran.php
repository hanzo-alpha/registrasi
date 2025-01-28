<?php

declare(strict_types=1);

namespace App\Filament\App\Pages;

use App\Enums\GolonganDarah;
use App\Enums\JenisKelamin;
use App\Enums\PaymentStatus;
use App\Enums\StatusBayar;
use App\Enums\StatusDaftar;
use App\Enums\StatusRegistrasi;
use App\Enums\TipeBayar;
use App\Enums\TipeKartuIdentitas;
use App\Enums\UkuranJersey;
use App\Models\Pembayaran;
use App\Models\Registrasi;
use App\Services\MidtransAPI;
use Awcodes\Shout\Components\Shout;
use Exception;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\CanUseDatabaseTransactions;
use Filament\Pages\Concerns\HasUnsavedDataChangesAlert;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Page;
use Filament\Support\Exceptions\Halt;
use Filament\Support\Facades\FilamentView;

use function Filament\Support\is_app_url;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Number;
use Illuminate\Support\Str;
use KodePandai\Indonesia\Models\City;
use KodePandai\Indonesia\Models\District;
use KodePandai\Indonesia\Models\Province;
use Livewire\Attributes\On;
use Parfaitementweb\FilamentCountryField\Forms\Components\Country;
use Throwable;

class Pendaftaran extends Page implements HasForms
{
    use CanUseDatabaseTransactions;
    use HasUnsavedDataChangesAlert;
    use InteractsWithFormActions;
    use InteractsWithForms;

    public ?Model $record = null;

    public ?array $data = [];

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $slug = 'pendaftaran';
    protected static string $view = 'filament.app.pages.pendaftaran';
    protected ?string $heading = 'Pendaftaran Online Peserta Bantaeng Trail Run 2025';
    protected ?string $subheading = 'Silahkan lengkapi data peserta di bawah ini.';

    public static function formOtomatis(): array
    {
        return [
            Shout::make('Informasi')
                ->columnSpanFull()
                ->content('Bagi 100 Pendaftar Pertama (Early Bird) untuk kategori 8K dan 15K akan mendapatkan keuntungan potongan harga. Jika sudah melebihi 100 pendaftar, akan dikenakan harga normal.')
                ->icon('heroicon-o-information-circle'),
            Forms\Components\Wizard::make([
                Forms\Components\Wizard\Step::make('Data Pribadi Peserta')
                    ->icon('heroicon-o-user')
                    ->completedIcon('heroicon-m-hand-thumb-up')
                    ->schema([
                        Section::make('Data Peserta')
                            ->schema([
                                Forms\Components\TextInput::make('uuid_registrasi')
                                    ->label('Kode Peserta')
                                    ->required()
                                    ->hidden()
                                    ->dehydrated()
                                    ->default(Str::uuid()->toString())
                                    ->suffixAction(
                                        Forms\Components\Actions\Action::make('copy')
                                            ->icon('heroicon-s-clipboard-document-check')
                                            ->action(function ($livewire, $state): void {
                                                $livewire->js(
                                                    'window.navigator.clipboard.writeText("' . $state . '");
                                        $tooltip("' . __('Copied to clipboard') . '", { timeout: 1500 });',
                                                );
                                            }),
                                    )
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('nama_lengkap')
                                    ->label('Nama Peserta')
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
                                Forms\Components\TextInput::make('kewarganegaraan')
                                    ->label('Kewarganegaraan')
                                    ->required(),
                            ])->columns(2),
                    ]),
                Forms\Components\Wizard\Step::make('Data Alamat Peserta')
                    ->icon('heroicon-o-map-pin')
                    ->completedIcon('heroicon-m-hand-thumb-up')
                    ->schema([
                        Section::make('Data Alamat Peserta')
                            ->schema([
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
                                Forms\Components\Grid::make()->schema([
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
                                ])->columns(2),
                            ])->columns(2),
                    ]),
                Forms\Components\Wizard\Step::make('Data Pendaftaran')
                    ->icon('heroicon-o-credit-card')
                    ->completedIcon('heroicon-m-hand-thumb-up')
                    ->schema([
                        Section::make('Data Pendaftaran')
                            ->schema([
                                Forms\Components\TextInput::make('jumlah_peserta')
                                    ->label('Jumlah Peserta Yang Didaftarkan')
                                    ->required()
                                    ->numeric()
                                    ->default(1)
                                    ->dehydrated()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('komunitas')
                                    ->label('Komunitas (Optional)')
                                    ->maxLength(255),
                                Forms\Components\Select::make('kategori_lomba')
                                    ->label('Kategori Lomba')
                                    ->relationship(
                                        name: 'kategori',
                                        titleAttribute: 'nama',
                                        modifyQueryUsing: function (Builder $query) {
                                            if (Registrasi::count() > 100) {
                                                return $query->whereNotIn('id', [1, 3]);
                                            }

                                            return $query->whereNotIn('id', [2, 4]);
                                        },
                                    )
                                    ->native(false)
                                    ->required(),
                                Forms\Components\Select::make('ukuran_jersey')
                                    ->label('Ukuran Jersey')
                                    ->native(false)
                                    ->options(UkuranJersey::class)
                                    ->enum(UkuranJersey::class)
                                    ->required(),
                            ])->columns(2),
                    ]),
            ])->submitAction(new HtmlString(Blade::render(<<<BLADE
                <x-filament::button
                    type="submit"
                    size="sm"
                >
                    Submit
                </x-filament::button>
            BLADE)))
                ->columnSpanFull(),
        ];
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    #[On('detailTransaction')]
    public function detailTransaction($result)
    {
        $orderId = $result['order_id'] ?? null;
        $registrasi = Registrasi::where('uuid_registrasi', $orderId)->first();
        $pembayaran = $registrasi->pembayaran;

        $uuidPembayaran = $pembayaran->uuid_pembayaran ?? Str::uuid()->toString();
        $orderId = $result['order_id'] ?? null;
        $transactionStatus = $result['transaction_status'] ?? null;
        $tipeBayar = $result['payment_type'] ?? TipeBayar::QRIS;
        $transactionTime = $result['transaction_time'] ?? null;
        $grossAmount = $result['gross_amount'] ?? 0;
        $redirectUrl = $result['finish_redirect_url'] ?? null;

        if (null === $pembayaran) {
            return Notification::make()
                ->title('Gagal')
                ->danger()
                ->body('Transaksi tidak ditemukan')
                ->icon('heroicon-o-check-circle')
                ->send();
        }

        $status = match ($transactionStatus) {
            PaymentStatus::SETTLEMENT->value, PaymentStatus::CAPTURE->value => StatusBayar::SUDAH_BAYAR,
            PaymentStatus::FAILURE->value => StatusBayar::GAGAL,
            PaymentStatus::PENDING->value => StatusBayar::PENDING,
            default => StatusBayar::BELUM_BAYAR,
        };

        $paymentTipe = match ($tipeBayar) {
            'qris', 'gopay', 'shopeepay' => TipeBayar::QRIS,
            default => TipeBayar::TRANSFER,
        };

        $statusRegistrasi = match ($transactionStatus) {
            PaymentStatus::SETTLEMENT->value, PaymentStatus::CAPTURE->value => StatusRegistrasi::BERHASIL,
            PaymentStatus::FAILURE->value => StatusRegistrasi::BATAL,
            PaymentStatus::PENDING->value => StatusRegistrasi::TUNDA,
            default => StatusRegistrasi::PROSES,
        };

        $registrasi->status_registrasi = $statusRegistrasi;

        $statusDaftar = (StatusRegistrasi::BERHASIL === $statusRegistrasi)
            ? StatusDaftar::TERDAFTAR
            : StatusDaftar::BELUM_TERDAFTAR;

        $pembayaran->tipe_pembayaran = $paymentTipe;
        $pembayaran->status_pembayaran = $status;
        $pembayaran->status_transaksi = $transactionStatus;
        $pembayaran->detail_transaksi = $result;
        $pembayaran->status_daftar = $statusDaftar;
        $pembayaran->lampiran = null;
        $pembayaran->save();
        $registrasi->save();

        $redirectUrl = $this->getRedirectUrl();
        $this->redirect($redirectUrl, navigate: FilamentView::hasSpaMode() && is_app_url($redirectUrl));

        if (StatusBayar::BELUM_BAYAR === $status) {
            return Notification::make()
                ->title('Belum ada Pembayaran')
                ->danger()
                ->body(
                    'Transaksi dengan order id: ' . $orderId . ' belum melakukan pembayaran sebesar Rp. '
                    . Number::format((int) $grossAmount, locale: 'id'),
                )
                ->icon('heroicon-o-x-circle')
                ->send();
        }

        if (StatusBayar::PENDING === $status) {
            return Notification::make()
                ->title('Menunggu Pembayaran')
                ->warning()
                ->body(
                    'Transaksi dengan order id: ' . $orderId . ' masih menunggu pembayaran sebesar Rp. '
                    . Number::format(
                        (int) $grossAmount,
                        locale: 'id',
                    ),
                )
                ->icon('heroicon-o-information-circle')
                ->send();
        }

        if (StatusBayar::GAGAL === $status) {
            return Notification::make()
                ->title('Pembayaran Gagal')
                ->danger()
                ->body(
                    'Transaksi dengan order id: ' . $orderId . ' gagal melakukan pembayaran sebesar Rp. '
                    . Number::format(
                        (int) $grossAmount,
                        locale: 'id',
                    ),
                )
                ->icon('heroicon-o-x-mark')
                ->send();
        }

        return Notification::make()
            ->title('Pembayaran Berhasil')
            ->success()
            ->body(
                'Transaksi dengan order id: ' . $orderId . ' telah berhasil dilakukan pada ' .
                Carbon::parse($transactionTime)->format('d/m/Y H:i:s') . '. sebesar Rp. ' .
                Number::format((int) $grossAmount, locale: 'id'),
            )
            ->icon('heroicon-o-check-circle')
            ->send();
    }

    /**
     * @throws Throwable
     */
    public function create(): void
    {
        try {
            $this->beginDatabaseTransaction();

            $data = $this->form->getState();

            $this->record = $this->handleRecordCreation($data);

            $this->form->model($this->getRecord());

            $this->commitDatabaseTransaction();
        } catch (Halt $exception) {
            $exception->shouldRollbackDatabaseTransaction() ?
                $this->rollBackDatabaseTransaction() :
                $this->commitDatabaseTransaction();

            return;
        } catch (Throwable $exception) {
            $this->rollBackDatabaseTransaction();

            throw $exception;
        }

        $this->rememberData();

        $this->getCreatedNotification()?->send();

    }

    public function getRecord(): ?Model
    {
        return $this->record;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema(static::formOtomatis())
            ->columns(2);
    }

    public function getModel(): string
    {
        return Registrasi::class;
    }

    public function getFormStatePath(): ?string
    {
        return 'data';
    }

    /**
     * @throws Exception
     */
    protected function handleRecordCreation(array $data): Model
    {
        $data['uuid_registrasi'] = \Str::uuid()->toString();
        $uuidPembayaran = \Str::uuid()->toString();
        $data['status_registrasi'] ??= StatusRegistrasi::BELUM_BAYAR;
        $data['provinsi'] ??= '74';
        $biaya = biaya_pendaftaran($data['kategori_lomba']);
        $data['jumlah_peserta'] ??= 1;
        $totalHarga = (int) $data['jumlah_peserta'] * $biaya;
        $namaKegiatan = 'Pendaftaran Bantaeng Trail Run 2025 Kategori Lomba - ' . $data['kategori_lomba'];
        $merchant = 'Freelethics Bantaeng';

        midtrans_config();

        $transactions = [
            'order_id' => $data['uuid_registrasi'],
            'gross_amount' => $totalHarga,
        ];

        $items = [
            'id' => \Str::uuid()->toString(),
            'price' => $biaya,
            'quantity' => (int) $data['jumlah_peserta'],
            'name' => $namaKegiatan,
            'merchant_name' => $merchant,
            'category' => $data['kategori_lomba'],
        ];

        $customers = [
            'first_name' => $data['nama_lengkap'],
            'last_name' => '',
            'email' => $data['email'],
            'phone' => $data['no_telp'],
            'address' => $data['alamat'],
            'shipping_address' => [
                'first_name' => $data['nama_lengkap'],
                'last_name' => '',
                'email' => $data['email'],
                'phone' => $data['no_telp'],
                'address' => $data['alamat'],
            ],
        ];

        $detailTransaksi = [
            'transactions' => $transactions,
            'items' => $items,
            'customers' => $customers,
        ];

        $data['is_earlybird'] = true;

        if (Registrasi::count() > 100) {
            $data['is_earlybird'] = false;
        }

        $record = new ($this->getModel())($data);

        $record->save();

        Pembayaran::create([
            'uuid_pembayaran' => $uuidPembayaran,
            'registrasi_id' => $record->id,
            'nama_kegiatan' => $namaKegiatan,
            'ukuran_jersey' => $data['ukuran_jersey'],
            'kategori_lomba' => $data['kategori_lomba'],
            'jumlah' => $data['jumlah_peserta'],
            'satuan' => 'Peserta',
            'harga_satuan' => $biaya,
            'total_harga' => $totalHarga,
            'tipe_pembayaran' => TipeBayar::QRIS,
            'status_pembayaran' => StatusBayar::PENDING,
            'status_transaksi' => PaymentStatus::PENDING,
            'status_daftar' => StatusDaftar::TERDAFTAR,
            'keterangan' => null,
            'detail_transaksi' => $detailTransaksi,
            'lampiran' => null,
        ]);

        $snapToken = MidtransAPI::getSnapTokenApi($transactions, $items, $customers);
        $this->dispatch('processPayment', ['snap_token' => $snapToken]);

        return $record;
    }

    protected function getCreatedNotification(): ?Notification
    {
        $title = $this->getCreatedNotificationTitle();

        if (blank($title)) {
            return null;
        }

        return Notification::make()
            ->success()
            ->title($title);
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return $this->getCreatedNotificationMessage() ?? __('filament-panels::resources/pages/create-record.notifications.created.title');
    }

    protected function getCreatedNotificationMessage(): ?string
    {
        return 'Pembayaran berhasil ditambahkan.';
    }

    protected function getForms(): array
    {
        return [
            'form' => $this->form($this->makeForm()
                ->operation('create')
                ->model($this->getModel())
                ->statePath($this->getFormStatePath())
                ->columns($this->hasInlineLabels() ? 1 : 2)
                ->inlineLabel($this->hasInlineLabels()), ),
        ];
    }

    protected function getSubmitFormAction(): Action
    {
        return $this->getCreateFormAction();
    }

    protected function getCreateFormAction(): Action
    {
        return Action::make('create')
            ->label('Pembayaran')
            ->icon('heroicon-o-credit-card')
            ->submit('create')
            ->keyBindings(['ctrl+s']);
    }

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return Pendaftaran::getUrl();
    }
}
