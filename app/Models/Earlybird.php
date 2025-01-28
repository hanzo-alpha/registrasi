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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Earlybird extends Model
{
    use HasWilayah;

    protected $table = 'earlybird';

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
        'kewarganegaraan',
        'ukuran_jersey',
        'jumlah_peserta',
        'kategori_lomba',
        'komunitas',
        'status_earlybird',
        'uuid_earlybird',
    ];

    public function pembayaran(): HasOne
    {
        return $this->hasOne(Pembayaran::class, 'registrasi_id', 'id')
            ->where('status_pendaftaran', StatusPendaftaran::EARLYBIRD);
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(\App\Models\KategoriLomba::class, 'kategori_lomba', 'id')
            ->where('kategori', StatusPendaftaran::EARLYBIRD);
    }

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'uuid_registrasi' => 'string',
            'ukuran_jersey' => UkuranJersey::class,
            'tipe_kartu_identitas' => TipeKartuIdentitas::class,
            'golongan_darah' => GolonganDarah::class,
            'status_earlybird' => StatusRegistrasi::class,
            'jenis_kelamin' => JenisKelamin::class,
        ];
    }
}
