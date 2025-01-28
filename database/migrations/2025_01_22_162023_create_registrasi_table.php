<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('registrasi', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid_registrasi')->nullable()->default(Str::uuid()->toString());
            $table->string('nama_lengkap');
            $table->string('email');
            $table->string('no_telp');
            $table->string('jenis_kelamin');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('alamat');
            $table->string('negara');
            $table->string('provinsi');
            $table->string('kabupaten');
            $table->string('kecamatan');
            $table->string('tipe_kartu_identitas');
            $table->string('nomor_kartu_identitas');
            $table->string('nama_kontak_darurat');
            $table->string('nomor_kontak_darurat');
            $table->string('golongan_darah');
            $table->string('kewarganegaraan');
            $table->string('ukuran_jersey');
            $table->unsignedBigInteger('jumlah_peserta');
            $table->string('kategori_lomba')->nullable();
            $table->string('komunitas')->nullable();
            $table->string('status_registrasi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registrasi');
    }
};
