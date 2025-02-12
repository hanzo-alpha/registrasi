<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistoriPembayaran extends Model
{
    protected $table = 'histori_pembayaran';

    protected $fillable = [
        'transaction_id',
        'order_id',
        'merchant_id',
        'status_message',
        'status_code',
        'signature_key',
        'settlement_time',
        'transaction_time',
        'payment_type',
        'gross_amount',
        'fraud_status',
        'currency',
        'transaction_status',
        'transaction_type',
        'acquirer',
        'issuer',
        'va_numbers',
        'payment_amounts',
        'expiry_time',
    ];

    protected function casts(): array
    {
        return [
            'va_numbers' => 'array',
            'payment_amounts' => 'array',
        ];
    }

    //    public function pembayaran(): BelongsTo
    //    {
    //        return $this->belongsTo(Pembayaran::class, 'pembayaran_id', 'id');
    //    }
}
