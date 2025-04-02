<?php

declare(strict_types=1);

use App\Filament\App\Pages\RegistrasiPeserta;
use App\Mail\PembayaranBerhasil;
use Illuminate\Support\Facades\Route;

Route::get('/', RegistrasiPeserta::class)->name('home');
Route::webhooks('registrasi-webhook', 'registrasi-webhook');
Route::webhooks('resend-notification', 'resend-webhook');

Route::get('/mailable', function () {
    $invoice = App\Models\Pembayaran::find(1);

    return new App\Mail\PembayaranBerhasil($invoice);
});
Route::get('/sentmail', function () {
    $invoice = App\Models\Pembayaran::find(1);
    return Mail::to(request()->user())->send(new PembayaranBerhasil($invoice));
});
