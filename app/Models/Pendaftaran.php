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
use Illuminate\Database\Eloquent\SoftDeletes;

class Pendaftaran extends Model
{
    use HasWilayah;
    use SoftDeletes;

    protected $table = 'pendaftaran';

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
        'uuid_pendaftaran',
    ];
    protected $with = ['kategori', 'pembayaran'];

    public function uniqueIds(): array
    {
        return ['uuid_pendaftaran'];
    }

    public function getRouteKeyName(): string
    {
        return 'uuid_pendaftaran';
    }

    public function pembayaran(): HasOne
    {
        return $this->hasOne(Pembayaran::class, 'pendaftaran_id', 'id');
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriLomba::class, 'kategori_lomba', 'id');
    }

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'uuid_pendaftaran' => 'string',
            'ukuran_jersey' => UkuranJersey::class,
            'tipe_kartu_identitas' => TipeKartuIdentitas::class,
            'golongan_darah' => GolonganDarah::class,
            'status_registrasi' => StatusRegistrasi::class,
            'jenis_kelamin' => JenisKelamin::class,
            'status_pendaftaran' => StatusPendaftaran::class,
        ];
    }
}
