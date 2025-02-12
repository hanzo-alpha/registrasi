<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\HistoriPembayaran;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin HistoriPembayaran */
class HistoriPembayaranResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'transaction_id' => $this->transaction_id,
            'order_id' => $this->order_id,
            'merchant_id' => $this->merchant_id,
            'status_message' => $this->status_message,
            'status_code' => $this->status_code,
            'signature_key' => $this->signature_key,
            'settlement_time' => $this->settlement_time,
            'transaction_time' => $this->transaction_time,
            'payment_type' => $this->payment_type,
            'gross_amount' => $this->gross_amount,
            'fraud_status' => $this->fraud_status,
            'currency' => $this->currency,
            'transaction_status' => $this->transaction_status,
            'transaction_type' => $this->transaction_type,
            'acquirer' => $this->acquirer,
            'issuer' => $this->issuer,
            'va_numbers' => $this->va_numbers,
            'payment_amounts' => $this->payment_amounts,
            'expiry_time' => $this->expiry_time,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
