<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\JenisBayar;
use App\Enums\PaymentStatus;
use App\Enums\StatusBayar;
use App\Enums\StatusDaftar;
use App\Enums\TipeBayar;
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
    ];

    public function uniqueIds(): array
    {
        return ['uuid_pembayaran'];
    }

    public function getRouteKeyName(): string
    {
        return 'uuid_pembayaran';
    }

    public function registrasi(): BelongsTo
    {
        return $this->belongsTo(Registrasi::class);
    }

    protected function casts(): array
    {
        return [
            'uuid_pembayaran' => 'string',
            'detail_transaksi' => 'array',
            'status_pembayaran' => StatusBayar::class,
            'status_transaksi' => PaymentStatus::class,
            'status_daftar' => StatusDaftar::class,
            'tipe_pembayaran' => TipeBayar::class,
        ];
    }

}
