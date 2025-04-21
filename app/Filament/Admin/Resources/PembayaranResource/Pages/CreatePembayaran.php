<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\PembayaranResource\Pages;

use App\Enums\PaymentStatus;
use App\Enums\StatusBayar;
use App\Enums\TipeBayar;
use App\Filament\Admin\Resources\PembayaranResource;
use App\Models\Pembayaran;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Str;

class CreatePembayaran extends CreateRecord
{
    protected static string $resource = PembayaranResource::class;

//    protected function handleRecordCreation(array $data): Model
//    {
//        $data['uuid_pembayaran'] ??= Str::uuid()->toString();
//        $data['order_id'] ??= Str::uuid()->toString();
//        $data['pendaftaran_id'] ??= '';
//        $data['nama_kegiatan'] ??= 'Pendaftaran Bantaeng Trail Run 2025';
//        $data['jumlah'] ??= 1;
//        $data['total_harga'] ??= 0;
//        $data['harga_satuan'] ??= 0;
//        $data['tipe_pembayaran'] ??= TipeBayar::TRANSFER;
//        $data['status_pembayaran'] ??= StatusBayar::PENDING;
//        $data['status_transaksi'] ??= PaymentStatus::PENDING;
//        $data['keterangan'] ??= null;
//        $data['detail_transaksi'] ??= [];
//        $data['lampiran'] ??= null;
//
//        $record = Pembayaran::create($data);
//
//        return $record;
//    }
}
