<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\JenisKelamin;
use App\Enums\TipeKartuIdentitas;
use App\Models\KategoriLomba;
use Illuminate\Database\Eloquent\Factories\Factory;

class RegistrasiFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'nama_lengkap' => fake()->name(),
            'email' => fake()->randomLetter() . fake()->unique()->safeEmail(),
            'no_telp' => fake()->phoneNumber(),
            'alamat' => fake()->address(),
            'jenis_kelamin' => array_rand(JenisKelamin::cases()),
            'tempat_lahir' => fake()->address(),
            'tanggal_lahir' => fake()->date(),
            'nama_kontak_darurat' => fake()->name(),
            'nomor_kontak_darurat' => fake()->phoneNumber(),
            'tipe_kartu_identitas' => array_rand(TipeKartuIdentitas::cases()),
            'nomor_kartu_identitas' => fake()->randomNumber(),
            'negara' => fake()->country(),
            'provinsi' => '74',
            'kabupaten' => '7414',
            'kecamatan' => '741401',
            'jumlah_peserta' => 1,
            'komunitas' => null,
            'kategori_lomba' => fake()->randomElements(KategoriLomba::pluck('id')->toArray()),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
