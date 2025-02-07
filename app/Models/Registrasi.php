<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\GolonganDarah;
use App\Enums\JenisKelamin;
use App\Enums\StatusPendaftaran;
use App\Enums\StatusRegistrasi;
use App\Enums\TipeKartuIdentitas;
use App\Enums\UkuranJersey;
use App\Traits\HasWilayah;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Registrasi extends Model
{
    use HasWilayah;

    protected $table = 'registrasi';

    protected $fillable = [
        'nama_lengkap',
        'email',
        'no_telp',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'negara',
        'provinsi',
        'kabupaten',
        'kecamatan',
        'tipe_kartu_identitas',
        'nomor_kartu_identitas',
        'nama_kontak_darurat',
        'nomor_kontak_darurat',
        'golongan_darah',
        'ukuran_jersey',
        'jumlah_peserta',
        'kategori_lomba',
        'komunitas',
        'status_registrasi',
        'status_pendaftaran',
        'uuid_registrasi',
    ];

    protected $with = ['kategori'];

    public function pembayaran(): HasOne
    {
        return $this->hasOne(Pembayaran::class, 'registrasi_id', 'id')
            ->where('status_pendaftaran', StatusPendaftaran::NORMAL);
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriLomba::class, 'kategori_lomba', 'id')
            ->where('kategori', StatusPendaftaran::NORMAL);
    }

    public function scopeKategoriLomba(Builder $query, string $kategoriLomba): Builder
    {
        return $query->where('kategori_lomba', $kategoriLomba);
    }

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'uuid_registrasi' => 'string',
            'ukuran_jersey' => UkuranJersey::class,
            'tipe_kartu_identitas' => TipeKartuIdentitas::class,
            'golongan_darah' => GolonganDarah::class,
            'status_registrasi' => StatusRegistrasi::class,
            'jenis_kelamin' => JenisKelamin::class,
            'status_pendaftaran' => StatusPendaftaran::class,
        ];
    }
}
