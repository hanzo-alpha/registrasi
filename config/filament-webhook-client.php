<?php

declare(strict_types=1);

return [

    'resources' => [
        'WebhookCallResource' => \Tapp\FilamentWebhookClient\Resources\WebhookCallResource::class,
    ],

    'models' => [
        'webhook-call' => \Spatie\WebhookClient\Models\WebhookCall::class,
    ],

    'policies' => [
        'webhook-call' => \Tapp\FilamentWebhookClient\Policies\WebhookCallPolicy::class,
    ],

    'navigation' => [
        'sort' => 1,
        'icon' => 'heroicon-o-book-open',
    ],

];
