<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('histori_pembayaran', function (Blueprint $table): void {
            $table->id();
            $table->string('transaction_id')->nullable();
            $table->string('order_id')->nullable();
            $table->string('merchant_id')->nullable();
            $table->string('status_message')->nullable();
            $table->string('status_code')->nullable();
            $table->string('signature_key')->nullable();
            $table->string('settlement_time')->nullable();
            $table->string('transaction_time')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('gross_amount')->nullable();
            $table->string('fraud_status')->nullable();
            $table->string('currency')->nullable();
            $table->string('transaction_status')->nullable();
            $table->string('transaction_type')->nullable();
            $table->string('acquirer')->nullable();
            $table->string('issuer')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('histori_pembayaran');
    }
};
