<?php

declare(strict_types=1);

namespace App\Filament\Admin\Widgets;

use App\Enums\StatusRegistrasi;
use App\Models\Earlybird;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Number;

class EarlybirdOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $countEarly = Earlybird::count();
        $totalBayarLunas = Earlybird::query()
            ->where('status_earlybird', StatusRegistrasi::BERHASIL)
            ->count();
        $totalFailed = Earlybird::query()
            ->where('status_earlybird', StatusRegistrasi::BATAL)
            ->count();
        $totalPending = Earlybird::query()
            ->whereIn('status_earlybird', [
                StatusRegistrasi::TUNDA,
                StatusRegistrasi::BELUM_BAYAR,
            ])
            ->count();
        return [
            Stat::make(
                'Jumlah Pendaftar Earlybird',
                Number::format($countEarly, 0, null, 'id') . ' Peserta',
            )
                ->description('Total Semua Pendaftar')
                ->color('primary'),
            Stat::make(
                'Jumlah Peserta Lunas',
                Number::format($totalBayarLunas, 0, null, 'id') . ' Peserta',
            )
                ->description('Pembayaran Lunas')
                ->color('info'),
            Stat::make(
                'Jumlah Peserta Pending',
                Number::format($totalPending, 0, null, 'id') . ' Peserta',
            )
                ->description('Pembayaran Pending')
                ->color('warning'),
            Stat::make(
                'Jumlah Peserta Kedaluwarsa',
                Number::format($totalFailed, 0, null, 'id') . ' Peserta',
            )
                ->description('Pembayaran Kedaluwarsa')
                ->color('danger'),
        ];
    }
}
