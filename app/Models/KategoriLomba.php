<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\StatusPendaftaran;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KategoriLomba extends Model
{
    protected $table = 'kategori_lomba';

    protected $fillable = [
        'nama',
        'harga',
        'warna',
        'kategori',
        'deskripsi',
    ];

    protected $casts = [
        'kategori' => StatusPendaftaran::class,
    ];

    public function registrasi(): BelongsTo
    {
        return $this->belongsTo(Registrasi::class, 'id', 'kategori_lomba');
    }
}
