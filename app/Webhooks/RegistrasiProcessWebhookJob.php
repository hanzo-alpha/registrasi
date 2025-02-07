<?php

declare(strict_types=1);

namespace App\Webhooks;

use App\Services\MidtransNotificationHandler;
use Spatie\WebhookClient\Jobs\ProcessWebhookJob as SpatieProcessWebhookJob;

class RegistrasiProcessWebhookJob extends SpatieProcessWebhookJob
{
    public function handle(): void
    {
        $data = $this->webhookCall->payload;
        //        MidtransNotificationHandler::getNotificationStatus();
        //Acknowledge you received the response
        http_response_code(200);
    }
}
