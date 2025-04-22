<?php

declare(strict_types=1);

namespace App\Filament\Admin\Widgets;

use App\Enums\StatusRegistrasi;
use App\Models\Pendaftaran;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Number;

class PendaftaranOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $countEarly = Pendaftaran::whereHas('kategori', fn($query) => $query->where('kategori', 'early_bird'))->count();
        $countNormal = Pendaftaran::whereHas('kategori', fn($query) => $query->where('kategori', 'normal'))->count();
        $countAll = Pendaftaran::count();

        $totalPending = Pendaftaran::query()
            ->whereIn('status_registrasi', [
                StatusRegistrasi::TUNDA,
                StatusRegistrasi::BELUM_BAYAR,
            ])
            ->count();
        return [
            Stat::make(
                'Jumlah Semua Peserta',
                Number::format($countAll, 0, null, 'id') . ' Peserta',
            )
                ->description('Jumlah Peserta Semua Kategori')
                ->color('primary'),
            Stat::make(
                'Jumlah Peserta Normal',
                Number::format($countNormal, 0, null, 'id') . ' Peserta',
            )
                ->description('Peserta Kategori Normal')
                ->color('info'),
            Stat::make(
                'Jumlah Peserta Early Bird',
                Number::format($countEarly, 0, null, 'id') . ' Peserta',
            )
                ->description('Peserta Kategori Early Bird')
                ->color('warning'),
            //            Stat::make(
            //                'Jumlah Peserta Kedaluwarsa',
            //                Number::format($totalFailed, 0, null, 'id') . ' Peserta',
            //            )
            //                ->description('Pembayaran Kedaluwarsa')
            //                ->color('danger'),
        ];
    }
}
