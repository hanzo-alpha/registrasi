<?php

declare(strict_types=1);

namespace App\Filament\Admin\Widgets;

use App\Enums\PaymentStatus;
use App\Enums\StatusPendaftaran;
use App\Models\Pembayaran;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Number;

class PembayaranOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalBayar = Pembayaran::sum('total_harga');
        $totalEarly = \App\Models\Earlybird::count();
        $totalBayarLunas = Pembayaran::where('status_pendaftaran', StatusPendaftaran::EARLYBIRD)
            ->where('status_transaksi', PaymentStatus::SETTLEMENT)
            ->sum('total_harga');
        $totalPending = Pembayaran::where('status_pendaftaran', StatusPendaftaran::EARLYBIRD)
            ->where('status_transaksi', PaymentStatus::PENDING)
            ->sum('total_harga');
        $totalFailed = Pembayaran::where('status_pendaftaran', StatusPendaftaran::EARLYBIRD)
            ->whereIn('status_transaksi', [
                PaymentStatus::CANCEL,
                PaymentStatus::FAILURE,
                PaymentStatus::DENY,
                PaymentStatus::EXPIRE,
            ])
            ->sum('total_harga');
        return [
            Stat::make('Total Pembayaran', Number::format($totalBayarLunas, 0, null, 'id'))
                ->description('Pembayaran Yang Berhasil')
                ->color('primary'),
            Stat::make('Total Pembayaran DiTunda', Number::format($totalPending, 0, null, 'id'))
                ->description('Pembayaran Yang Tertunda')
                ->color('info'),
            Stat::make('Total Pembayaran', Number::format($totalFailed, 0, null, 'id'))
                ->description('Pembayaran Yang Kedaluwarsa')
                ->color('danger'),
            Stat::make('Total Pembayaran', Number::format($totalBayar, 0, null, 'id'))
                ->description('Total Semua Pembayaran')
                ->color('warning'),
        ];
    }
}
