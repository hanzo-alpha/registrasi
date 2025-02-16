<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\PaymentStatus;
use App\Enums\StatusBayar;
use App\Enums\StatusRegistrasi;
use App\Models\Pembayaran;
use Exception;

use function hash;

use Illuminate\Support\Facades\Http;
use Midtrans\Snap;

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
        $signature = $orderId . $statusCode . $amount . $serverKey;
        $hash = hash('sha512', $signature);
        return hash_equals($signatureKey, $hash);
    }

    /**
     * @throws \Illuminate\Http\Client\ConnectionException
     */
    public static function getTransactionStatus($orderId): bool|string|int|null|array
    {
        $data = [];
        $data['responses'] = [];
        $data['sukses'] = false;

        if ($orderId) {
            $url = config('midtrans.is_production')
                ? 'https://api.midtrans.com/v2/' . $orderId . '/status'
                : 'https://api.sandbox.midtrans.com/v2/' . $orderId . '/status';

            $response = config('midtrans.is_production')
                ? Http::acceptJson()->withBasicAuth(config('midtrans.production.server_key'), '')->get($url)
                : Http::acceptJson()->withBasicAuth(config('midtrans.sb.server_key'), '')->get($url);

            $data['responses'] = $response->json();
            $data['sukses'] = true;
        }

        return $data;
    }

    public static function getStatusMessage($orderId): string|null|array
    {
        $statusMessage = null;
        $status = 'danger';

        $url = config('midtrans.is_production')
            ? 'https://api.midtrans.com/v2/' . $orderId . '/status'
            : 'https://api.sandbox.midtrans.com/v2/' . $orderId . '/status';

        $response = config('midtrans.is_production')
            ? Http::acceptJson()->withBasicAuth(config('midtrans.production.server_key'), '')->get($url)
            : Http::acceptJson()->withBasicAuth(config('midtrans.sb.server_key'), '')->get($url);

        $details = $response->collect()->except(['id'])->toArray();
        $responses = $response->collect()->toArray();
        if (count($responses) <= 0) {
            $statusMessage = 'Transaksi Dengan Order ID : ' . $orderId . ' tidak ditemukan';
        }
        $transaction = $responses['transaction_status'] ?? null;
        $type = $responses['payment_type'] ?? null;
        $order_id = $responses['order_id'] ?? null;
        $fraud = $responses['fraud_status'] ?? null;

        $pembayaran = Pembayaran::where('order_id', $order_id)->first();
        $registrasi = $pembayaran->earlybird;

        $pembayaran->detail_transaksi = $details;

        if ('capture' === $transaction) {
            if ('credit_card' === $type) {
                if ('accept' === $fraud) {
                    // TODO set payment status in merchant's database to 'Success'
                    $pembayaran->status_transaksi = PaymentStatus::CAPTURE;
                    $pembayaran->status_pembayaran = StatusBayar::SUDAH_BAYAR;
                    $registrasi->status_earlybird = StatusRegistrasi::BERHASIL;
                    $status = 'success';
                    $statusMessage = 'Transaksi order_id: ' . $order_id . ' berhasil ditangkap menggunakan ' . $type;
                }
            }
        } else {
            if ('settlement' === $transaction) {
                // TODO set payment status in merchant's database to 'Settlement'
                $pembayaran->status_transaksi = PaymentStatus::SETTLEMENT;
                $pembayaran->status_pembayaran = StatusBayar::SUDAH_BAYAR;
                $registrasi->status_earlybird = StatusRegistrasi::BERHASIL;
                $status = 'success';
                $statusMessage = 'Transaksi order_id: ' . $order_id . ' berhasil ditransfer menggunakan ' . $type;
            } else {
                if ('pending' === $transaction) {
                    // TODO set payment status in merchant's database to 'Pending'
                    $pembayaran->status_transaksi = PaymentStatus::PENDING;
                    $pembayaran->status_pembayaran = StatusBayar::PENDING;
                    $registrasi->status_earlybird = StatusRegistrasi::TUNDA;
                    $status = 'info';
                    $statusMessage = 'Menunggu nasabah menyelesaikan transaksi order_id: ' . $order_id . ' menggunakan '
                        . $type;
                } else {
                    if ('deny' === $transaction) {
                        // TODO set payment status in merchant's database to 'Denied'
                        $pembayaran->status_transaksi = PaymentStatus::DENY;
                        $pembayaran->status_pembayaran = StatusBayar::GAGAL;
                        $registrasi->status_earlybird = StatusRegistrasi::BATAL;
                        $status = 'danger';
                        $statusMessage = 'Pembayaran menggunakan ' . $type . ' untuk transaksi order_id: ' . $order_id . ' ditolak.';
                    } else {
                        if ('expire' === $transaction) {
                            // TODO set payment status in merchant's database to 'expire'
                            $pembayaran->status_transaksi = PaymentStatus::EXPIRE;
                            $pembayaran->status_pembayaran = StatusBayar::GAGAL;
                            $registrasi->status_earlybird = StatusRegistrasi::BATAL;
                            $status = 'warning';
                            $statusMessage = 'Pembayaran menggunakan ' . $type . ' untuk transaksi order_id: ' . $order_id . ' kedaluwarsa.';
                        } else {
                            if ('cancel' === $transaction) {
                                // TODO set payment status in merchant's database to 'Denied'
                                $pembayaran->status_transaksi = PaymentStatus::CANCEL;
                                $pembayaran->status_pembayaran = StatusBayar::GAGAL;
                                $registrasi->status_earlybird = StatusRegistrasi::BATAL;
                                $status = 'warning';
                                $statusMessage = 'Pembayaran menggunakan ' . $type . ' untuk transaksi order_id: ' . $order_id . ' dibatalkan.';
                            }
                        }
                    }
                }
            }
        }

        if (null === $statusMessage) {
            $pembayaran->delete();
            $registrasi->delete();
        } else {
            $pembayaran->save();
            $registrasi->save();
        }

        return [
            'status' => $status,
            'status_message' => $statusMessage,
        ];
    }

    /**
     * @throws Exception
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
