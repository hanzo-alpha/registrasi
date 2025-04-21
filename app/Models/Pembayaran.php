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
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pembayaran extends Model
{
    use HasUuids;
    use HasWilayah;
    use SoftDeletes;

    protected $table = 'pembayaran';

    protected $fillable = [
        'uuid_pembayaran',
        'order_id',
        'pendaftaran_id',
        'nama_kegiatan',
        'jumlah',
        'satuan',
        'harga_satuan',
        'total_harga',
        'tipe_pembayaran',
        'status_pembayaran',
        'status_transaksi',
        'keterangan',
        'detail_transaksi',
        'lampiran',
    ];

    public function uniqueIds(): array
    {
        return ['uuid_pembayaran', 'order_id'];
    }

    public function getRouteKeyName(): string
    {
        return 'uuid_pembayaran';
    }

    public function pendaftaran(): BelongsTo
    {
        return $this->belongsTo(Pendaftaran::class, 'pendaftaran_id', 'id');
    }

    public function peserta(): HasOneThrough
    {
        return $this->hasOneThrough(Pendaftaran::class, Peserta::class, 'id', 'peserta_id', 'pendaftaran_id');
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
            'tipe_pembayaran' => TipeBayar::class,
        ];
    }

}
