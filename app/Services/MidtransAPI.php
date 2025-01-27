<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\PaymentStatus;
use App\Enums\StatusBayar;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Midtrans\Snap;
use function hash;

class MidtransAPI
{
    public string $serverKey;
    public bool $isProduction;
    public bool $isSanitized = false;
    public bool $is3ds = true;
    public array $transaction_details = [];
    public array $customer_details = [];

    /**
     * Accept : application/json
     * Content-Type: application/json
     * response: token, redirect_url
     * */
    public static function getSnapTransactionsEndpoint(bool $isProduction = false): string
    {
        return $isProduction
            ? 'https://api.sandbox.midtrans.com/snap/v1/transactions'
            : 'https://api.midtrans.com/snap/v1/transactions/';
    }

    public static function setBillingAddress(array $billing): array
    {
        return [
            'billing_address' => static::transformToArray($billing),
        ];
    }

    public static function transformToArray(array $data)
    {
        $collect = collect();
        foreach ($data as $key => $value) {
            $collect->put($key, $value);
        }
        return $collect->toArray();
    }

    public static function getBillingAddress(array $billing): array
    {
        return static::setCustomerDetails($billing);
    }

    public static function setCustomerDetails(array $customer_details): array
    {
        return [
            'customer_details' => static::transformToArray($customer_details),
        ];
    }

    public static function verifySignatureKey(
        string $signatureKey,
        string $orderId,
        string $statusCode,
        int $amount,
        string $serverKey,
    ): bool {
        $signature = $orderId.$statusCode.$amount.$serverKey;
        $hash = hash('sha512', $signature);
        return hash_equals($signatureKey, $hash);
    }

    /**
     * @throws \Illuminate\Http\Client\ConnectionException
     */
    public static function getTransactionStatus(string $orderId): Notification
    {
        $url = 'https://api.sandbox.midtrans.com/v2/'.$orderId.'/status';
        $response = Http::acceptJson()->withBasicAuth(config('midtrans.sb.server_key'), '')->get($url);
        $detail = $response->json();

        if (null === $detail || blank($detail)) {
            return Notification::make()
                ->title('Gagal mengambil status transaksi')
                ->danger()
                ->icon('heroicon-o-information-circle')
                ->send();
        }

        $update = \App\Models\Pembayaran::where('uuid_registrasi', $detail['order_id'])->first();

        $status = match ($detail['transaction_status']) {
            PaymentStatus::SETTLEMENT->value, PaymentStatus::CAPTURE->value => StatusBayar::SUDAH_BAYAR,
            PaymentStatus::FAILURE->value => StatusBayar::GAGAL,
            PaymentStatus::PENDING->value => StatusBayar::PENDING,
            default => StatusBayar::BELUM_BAYAR,
        };

        $update->status_pembayaran = $status;
        $update->detail_transaksi = $detail;

        $update->save();

        return Notification::make()
            ->title('Detail Berhasil di simpan')
            ->success()
            ->icon('heroicon-o-check-circle')
            ->send();
    }

    /**
     * @throws \Exception
     */
    public static function getSnapTokenApi(?array $transaction, ?array $items, ?array $customer): string
    {
        midtrans_config();

        $params = array_merge(
            static::getTransactionDetails($transaction),
            static::getItemDetails($items),
            static::getCustomerDetails($customer),
        );

        return Snap::getSnapToken($params);
    }

    public static function getTransactionDetails(array $transaction_details): array
    {
        return static::setTransactionDetails($transaction_details);
    }

    public static function setTransactionDetails(array $transaction_details): array
    {
        return [
            'transaction_details' => static::transformToArray($transaction_details),
        ];
    }

    public static function getItemDetails(array $items): array
    {
        return static::setItemDetails($items);
    }

    public static function setItemDetails(array $items): array
    {
        return [
            'item_details' => [static::transformToArray($items)],
        ];
    }

    public static function getCustomerDetails(array $customer_details): array
    {
        return static::setCustomerDetails($customer_details);
    }
}
