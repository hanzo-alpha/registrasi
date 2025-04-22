<?php

declare(strict_types=1);

namespace App\Filament\Admin\Widgets;

use App\Enums\StatusBayar;
use App\Models\Pembayaran;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Number;

class PembayaranOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalBayarLunas = Pembayaran::query()
            ->whereHas('pendaftaran', fn($query) => $query->whereHas('kategori', fn($query) => $query->where('kategori', 'early_bird')))
            ->where('status_pembayaran', StatusBayar::SUDAH_BAYAR)
            ->sum('total_harga');

        $totalEarlyBird = Pembayaran::query()
            ->whereHas('pendaftaran', fn($query) => $query->whereHas('kategori', fn($query) => $query->where('kategori', 'early_bird')))
            ->where('status_pembayaran', StatusBayar::SUDAH_BAYAR)
            ->sum('total_harga');

        $totalNormal = Pembayaran::query()
            ->whereHas('pendaftaran', fn($query) => $query->whereHas('kategori', fn($query) => $query->where('kategori', 'normal')))
            ->where('status_pembayaran', StatusBayar::SUDAH_BAYAR)
            ->sum('total_harga');

        $totalBayarLunasEarlybird = Pembayaran::where('status_pembayaran', StatusBayar::PENDING)
            ->sum('total_harga');
        $totalBayarLunasNormal = Pembayaran::where('status_pembayaran', StatusBayar::BELUM_BAYAR)
            ->sum('total_harga');

        return [
            Stat::make('Total Pembayaran Lunas', Number::format($totalBayarLunas, 0, null, 'id'))
                ->description('Jumlah Pembayaran Lunas')
                ->color('primary'),
            Stat::make('Total Pembayaran Pending', Number::format($totalBayarLunasEarlybird, 0, null, 'id'))
                ->description('Jumlah Pembayaran Pending')
                ->color('yellow'),
            //            Stat::make('Total Belum Membayar', Number::format($totalBayarLunasNormal, 0, null, 'id'))
            //                ->description('Jumlah Belum Membayar')
            //                ->color('secondary'),
            Stat::make('Total Pembayaran Earlybird', Number::format($totalEarlyBird, 0, null, 'id'))
                ->description('Kategori Earlybird')
                ->color('success'),
            Stat::make('Total Pembayaran Normal', Number::format($totalNormal, 0, null, 'id'))
                ->description('Kategori Normal')
                ->color('danger'),
        ];
    }
}
