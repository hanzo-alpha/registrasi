<?php

namespace App\Models;

use App\Enums\GolonganDarah;
use App\Enums\JenisKelamin;
use App\Enums\KategoriLomba;
use App\Enums\StatusRegistrasi;
use App\Enums\TipeKartuIdentitas;
use App\Enums\UkuranJersey;
use App\Traits\HasWilayah;
use Illuminate\Database\Eloquent\Model;
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
        'kewarganegaraan',
        'ukuran_jersey',
        'jumlah_peserta',
        'kategori_lomba',
        'status_registrasi',
        'uuid_registrasi',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'uuid_registrasi' => 'string',
            'ukuran_jersey' => UkuranJersey::class,
            'tipe_kartu_identitas' => TipeKartuIdentitas::class,
            'kategori_lomba' => KategoriLomba::class,
            'golongan_darah' => GolonganDarah::class,
            'status_registrasi' => StatusRegistrasi::class,
            'jenis_kelamin' => JenisKelamin::class,
        ];
    }

    public function pembayaran(): HasOne
    {
        return $this->hasOne(Pembayaran::class, 'registrasi_id', 'id');
    }
}
