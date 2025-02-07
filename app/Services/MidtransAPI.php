<?php

declare(strict_types=1);

namespace App\Services;

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
    public static function getTransactionStatus(string $orderId): bool|string|int|null|array
    {
        $data = [];
        $url = config('midtrans.is_production')
            ? 'https://api.midtrans.com/v2/' . $orderId . '/status'
            : 'https://api.sandbox.midtrans.com/v2/' . $orderId . '/status';

        $response = config('midtrans.is_production')
            ? Http::acceptJson()->withBasicAuth(config('midtrans.production.server_key'), '')->get($url)
            : Http::acceptJson()->withBasicAuth(config('midtrans.sb.server_key'), '')->get($url);

        $data['responses'] = $response->json();

        if ( ! null === $data['responses'] || ! blank($data['responses'])) {
            $sukses = true;
        }

        $data['sukses'] = $sukses;
        return $data;
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
