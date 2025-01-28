<?php

declare(strict_types=1);

use App\Enums\StatusPendaftaran;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('registrasi_earlybird', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid_registrasi_earlybird')->nullable()->default(Str::uuid()->toString());
            $table->foreignId('registrasi_id')
                ->nullable()
                ->constrained('registrasi');
            $table->string('kategori_lomba')->nullable();
            $table->double('harga_tiket')->nullable()->default(0);
            $table->string('status_pendaftaran')->nullable()->default(StatusPendaftaran::EARLYBIRD);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registrasi_earlybird');
    }
};
