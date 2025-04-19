<?php

declare(strict_types=1);

namespace App\Filament\Exports;

use App\Enums\PaymentStatus;
use App\Enums\StatusBayar;
use App\Enums\StatusDaftar;
use App\Enums\StatusPendaftaran;
use App\Enums\TipeBayar;
use App\Enums\UkuranJersey;
use App\Models\Pembayaran;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Number;

class PembayaranExporter extends Exporter
{
    protected static ?string $model = Pembayaran::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('uuid_pembayaran')
                ->label('UUID Pembayaran'),
            ExportColumn::make('pendaftaran.nama_lengkap')
                ->label('Nama Lengkap'),
            ExportColumn::make('nama_kegiatan')
                ->label('Nama Kegiatan'),
            ExportColumn::make('ukuran_jersey')
                ->label('Ukuran Jersey')
                ->formatStateUsing(function ($state) {
                    return match ($state->value) {
                        UkuranJersey::M_MEN->value => UkuranJersey::M_MEN->value,
                        UkuranJersey::M_WOMEN->value => UkuranJersey::M_WOMEN->value,
                        UkuranJersey::XS_MEN->value => UkuranJersey::XS_MEN->value,
                        UkuranJersey::XS_WOMEN->value => UkuranJersey::XS_WOMEN->value,
                        UkuranJersey::XXS_MEN->value => UkuranJersey::XXS_MEN->value,
                        UkuranJersey::XXS_WOMEN->value => UkuranJersey::XXS_WOMEN->value,
                        UkuranJersey::XL_MEN->value => UkuranJersey::XL_MEN->value,
                        UkuranJersey::XL_WOMEN->value => UkuranJersey::XL_WOMEN->value,
                        UkuranJersey::XXL_MEN->value => UkuranJersey::XXL_MEN->value,
                        UkuranJersey::XXL_WOMEN->value => UkuranJersey::XXL_WOMEN->value,
                        UkuranJersey::XXXL_MEN->value => UkuranJersey::XXXL_MEN->value,
                        UkuranJersey::XXXL_WOMEN->value => UkuranJersey::XXXL_WOMEN->value,
                        UkuranJersey::L_MEN->value => UkuranJersey::L_MEN->value,
                        UkuranJersey::L_WOMEN->value => UkuranJersey::L_WOMEN->value,
                        UkuranJersey::S_MEN->value => UkuranJersey::S_MEN->value,
                        UkuranJersey::S_WOMEN->value => UkuranJersey::S_WOMEN->value,
                    };
                }),
            ExportColumn::make('kategori.nama')
                ->label('Kategori Lomba'),
            ExportColumn::make('jumlah'),
            ExportColumn::make('satuan'),
            ExportColumn::make('harga_satuan')
                ->label('Harga Satuan')
                ->formatStateUsing(fn($state) => Number::currency($state, locale: 'id', precision: 0)),
            ExportColumn::make('total_harga')
                ->label('Total Harga')
                ->formatStateUsing(fn($state) => Number::currency($state, locale: 'id', precision: 0)),
            ExportColumn::make('tipe_pembayaran')
                ->label('Tipe Pembayaran')
                ->formatStateUsing(function ($state) {
                    return match ($state->value) {
                        TipeBayar::QRIS->value => TipeBayar::QRIS->value,
                        TipeBayar::TRANSFER->value => TipeBayar::TRANSFER->value,
                    };
                }),
            ExportColumn::make('status_pembayaran')
                ->label('Status Pembayaran')
                ->formatStateUsing(function ($state) {
                    return match ($state->value) {
                        StatusBayar::BELUM_BAYAR->value => StatusBayar::BELUM_BAYAR->value,
                        StatusBayar::SUDAH_BAYAR->value => StatusBayar::SUDAH_BAYAR->value,
                        StatusBayar::GAGAL->value => StatusBayar::GAGAL->value,
                        StatusBayar::PENDING->value => StatusBayar::PENDING->value,
                    };
                }),
            ExportColumn::make('status_transaksi')
                ->label('Status Transaksi')
                ->formatStateUsing(function ($state) {
                    return match ($state->value) {
                        PaymentStatus::PENDING->value => PaymentStatus::PENDING->value,
                        PaymentStatus::SETTLEMENT->value => PaymentStatus::SETTLEMENT->value,
                        PaymentStatus::DENY->value => PaymentStatus::DENY->value,
                        PaymentStatus::CANCEL->value => PaymentStatus::CANCEL->value,
                        PaymentStatus::CAPTURE->value => PaymentStatus::CAPTURE->value,
                        PaymentStatus::AUTHORIZE->value => PaymentStatus::AUTHORIZE->value,
                        PaymentStatus::CHARGEBACK->value => PaymentStatus::CHARGEBACK->value,
                        PaymentStatus::EXPIRE->value => PaymentStatus::EXPIRE->value,
                        PaymentStatus::FAILURE->value => PaymentStatus::FAILURE->value,
                        PaymentStatus::PARTIAL_CHARGEBACK->value => PaymentStatus::PARTIAL_CHARGEBACK->value,
                        PaymentStatus::PARTIAL_REFUND->value => PaymentStatus::PARTIAL_REFUND->value,
                        PaymentStatus::REFUND->value => PaymentStatus::REFUND->value,
                    };
                }),
            ExportColumn::make('status_daftar')
                ->label('Status Daftar')
                ->formatStateUsing(function ($state) {
                    return match ($state->value) {
                        StatusDaftar::BELUM_TERDAFTAR->value => StatusDaftar::BELUM_TERDAFTAR->value,
                        StatusDaftar::TERDAFTAR->value => StatusDaftar::TERDAFTAR->value,
                    };
                }),
            ExportColumn::make('status_pendaftaran')
                ->label('Status Pendaftaran')
                ->formatStateUsing(function ($state) {
                    return match ($state->value) {
                        StatusPendaftaran::EARLYBIRD->value => StatusPendaftaran::EARLYBIRD->value,
                        StatusPendaftaran::NORMAL->value => StatusPendaftaran::NORMAL->value,
                    };
                }),
            ExportColumn::make('keterangan')
                ->label('Keterangan'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Data pembayaran anda telah di eksport dan ' . number_format($export->successful_rows) . ' ' . str('baris')->plural($export->successful_rows) . ' berhasil diekspor.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' gagal di ekspor.';
        }

        return $body;
    }
}
