<?php

declare(strict_types=1);

namespace App\Models;

use App\Casts\MoneyCast;
use App\Enums\KategoriLomba;
use App\Enums\StatusPendaftaran;
use App\Traits\HasWilayah;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistrasiEarlybird extends Model
{
    use HasWilayah;

    protected $table = 'registrasi_earlybird';

    protected $fillable = [
        'uuid_registrasi_earlybird',
        'registrasi_id',
        'kategori_lomba',
        'harga_tiket',
        'status_pendaftaran',
    ];

    public function registrasi(): BelongsTo
    {
        return $this->belongsTo(Registrasi::class, 'registrasi_id', 'id');
    }

    protected function casts(): array
    {
        return [
            'uuid_registrasi_earlybird' => 'string',
            'kategori_lomba' => KategoriLomba::class,
            'status_pendaftaran' => StatusPendaftaran::class,
            'harga_tiket' => MoneyCast::class,
        ];
    }
}
