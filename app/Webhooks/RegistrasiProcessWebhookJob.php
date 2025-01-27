<?php

namespace App\Webhooks;

use Filament\Notifications\Notification;
use Spatie\WebhookClient\Jobs\ProcessWebhookJob as SpatieProcessWebhookJob;

class RegistrasiProcessWebhookJob extends SpatieProcessWebhookJob
{
    public function handle(): Notification
    {
        return Notification::make()
            ->title('Testing Webhook')
        ->body('Testing Webhook');
//         $this->webhookCall; // contains an instance of `WebhookCall`

        // perform the work here
    }
}
