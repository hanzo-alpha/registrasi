<?php

declare(strict_types=1);

use App\Livewire\Frontend\Index;
use Illuminate\Support\Facades\Route;

//Route::get('/', Index::class)->name('home');
//Route::get('/', fn() => redirect()->route('filament.app.pages.earlybird'))->name('home');
Route::get('/', fn() => redirect()->route('filament.app.pages.pendaftaran'))->name('home');
Route::webhooks('registrasi-webhook');
