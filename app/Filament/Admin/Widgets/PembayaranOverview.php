<?php

declare(strict_types=1);

namespace App\Filament\Admin\Widgets;

use App\Enums\PaymentStatus;
use App\Enums\StatusBayar;
use App\Enums\StatusPendaftaran;
use App\Models\Pembayaran;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Number;

class PembayaranOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalBayar = Pembayaran::query()
            ->where('status_pembayaran', StatusBayar::SUDAH_BAYAR)
            ->sum('total_harga');
        $totalBayarLunasEarlybird = Pembayaran::where('status_pendaftaran', StatusPendaftaran::EARLYBIRD)
            ->where('status_transaksi', PaymentStatus::SETTLEMENT)
            ->sum('total_harga');
        $totalBayarLunasNormal = Pembayaran::where('status_pendaftaran', StatusPendaftaran::NORMAL)
            ->where('status_transaksi', PaymentStatus::SETTLEMENT)
            ->sum('total_harga');
        return [
            Stat::make('Total Pembayaran Earlybird', Number::format($totalBayarLunasEarlybird, 0, null, 'id'))
                ->description('Pembayaran Earlybird Berhasil')
                ->color('primary'),
            Stat::make('Total Pembayaran Normal', Number::format($totalBayarLunasNormal, 0, null, 'id'))
                ->description('Pembayaran Normal Berhasil')
                ->color('yellow'),
            Stat::make('Total Semua Pembayaran', Number::format($totalBayar, 0, null, 'id'))
                ->description('Pembayaran Earlybird dan Normal')
                ->color('secondary'),
        ];
    }
}
