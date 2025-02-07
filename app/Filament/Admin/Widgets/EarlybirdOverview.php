<?php

declare(strict_types=1);

namespace App\Filament\Admin\Widgets;

use App\Enums\PaymentStatus;
use App\Enums\StatusPendaftaran;
use App\Models\Pembayaran;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Number;

class EarlybirdOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $countEarly = \App\Models\Earlybird::count();
        $totalBayarLunas = Pembayaran::where('status_pendaftaran', StatusPendaftaran::EARLYBIRD)
            ->where('status_transaksi', PaymentStatus::SETTLEMENT)
            ->count();
        $totalPending = Pembayaran::where('status_pendaftaran', StatusPendaftaran::EARLYBIRD)
            ->where('status_transaksi', PaymentStatus::PENDING)
            ->count();
        return [
            Stat::make('Jumlah Pendaftar Earlybird', Number::format($countEarly, 0, null, 'id'))
                ->description('Total Semua Pendaftar Earlybird')
                ->color('success'),
            Stat::make('Jumlah Pembayaran Lunas', Number::format($totalBayarLunas, 0, null, 'id'))
                ->description('Total Semua Pembayaran Lunas')
                ->color('danger'),
            Stat::make('Jumlah Pembayaran Pending', Number::format($totalPending, 0, null, 'id'))
                ->description('Total Semua Pembayaran Pending')
                ->color('info'),
        ];
    }
}
