<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PaymentStatus;
use App\Enums\StatusBayar;
use App\Enums\StatusDaftar;
use App\Enums\StatusPendaftaran;
use App\Enums\TipeBayar;
use App\Enums\UkuranJersey;
use App\Traits\HasWilayah;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pembayaran extends Model
{
    use HasUuids;
    use HasWilayah;

    protected $table = 'pembayaran';

    protected $fillable = [
        'uuid_pembayaran',
        'order_id',
        'registrasi_id',
        'nama_kegiatan',
        'ukuran_jersey',
        'kategori_lomba',
        'jumlah',
        'satuan',
        'harga_satuan',
        'total_harga',
        'tipe_pembayaran',
        'status_pembayaran',
        'status_transaksi',
        'status_daftar',
        'keterangan',
        'detail_transaksi',
        'lampiran',
        'is_earlybird',
        'status_pendaftaran',
    ];

    public function uniqueIds(): array
    {
        return ['uuid_pembayaran', 'order_id'];
    }

    public function getRouteKeyName(): string
    {
        return 'uuid_pembayaran';
    }

    public function registrasi(): BelongsTo
    {
        return $this->belongsTo(Registrasi::class, 'registrasi_id', 'id');
    }

    public function earlybird(): BelongsTo
    {
        return $this->belongsTo(Earlybird::class, 'registrasi_id', 'id');
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriLomba::class, 'kategori_lomba', 'id');
    }

    protected function casts(): array
    {
        return [
            'uuid_pembayaran' => 'string',
            'order_id' => 'string',
            'detail_transaksi' => 'array',
            'status_pembayaran' => StatusBayar::class,
            'status_transaksi' => PaymentStatus::class,
            'status_daftar' => StatusDaftar::class,
            'tipe_pembayaran' => TipeBayar::class,
            'status_pendaftaran' => StatusPendaftaran::class,
            'ukuran_jersey' => UkuranJersey::class,
        ];
    }

}
