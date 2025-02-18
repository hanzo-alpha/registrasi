<?php

declare(strict_types=1);

use App\Webhooks\Registrasi\RegistrasiProcessWebhookJob;
use App\Webhooks\Resend\ResendProcessWebhookJob;
use App\Webhooks\Resend\ResendProfileWebhook;
use App\Webhooks\Resend\ResendResponseWebhook;
use App\Webhooks\Resend\ResendSignatureValidator;

return [
    'configs' => [
        [
            'name' => 'registrasi-webhook',
            'signing_secret' => env('WEBHOOK_CLIENT_SECRET'),
            'signature_header_name' => 'Signature',
            //            'signature_validator' => \Spatie\WebhookClient\SignatureValidator\DefaultSignatureValidator::class,
            'signature_validator' => \App\Webhooks\Registrasi\RegistrasiSignatureValidator::class,
            //            'webhook_profile' => \Spatie\WebhookClient\WebhookProfile\ProcessEverythingWebhookProfile::class,
            'webhook_profile' => \App\Webhooks\Registrasi\RegistrasiProfileWebhook::class,
            //            'webhook_response' => \Spatie\WebhookClient\WebhookResponse\DefaultRespondsTo::class,
            'webhook_response' => \App\Webhooks\Registrasi\RegistrasiResponseWebhook::class,
            'webhook_model' => \Spatie\WebhookClient\Models\WebhookCall::class,
            'store_headers' => [
                'content-type' => 'application/json',
            ],
            'process_webhook_job' => RegistrasiProcessWebhookJob::class,
        ],
        [
            'name' => 'resend-webhook',
            'signing_secret' => env('WEBHOOK_CLIENT_SECRET'),
            'signature_header_name' => 'Signature',
            'signature_validator' => ResendSignatureValidator::class,
            'webhook_profile' => ResendProfileWebhook::class,
            'webhook_response' => ResendResponseWebhook::class,
            'webhook_model' => \Spatie\WebhookClient\Models\WebhookCall::class,
            'store_headers' => [
                'content-type' => 'application/json',
            ],
            'process_webhook_job' => ResendProcessWebhookJob::class,
        ],
    ],

    /*
     * The integer amount of days after which models should be deleted.
     *
     * It deletes all records after 30 days. Set to null if no models should be deleted.
     */
    'delete_after_days' => 30,

    /*
     * Should a unique token be added to the route name
     */
    'add_unique_token_to_route_name' => true,
];
