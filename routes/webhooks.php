<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use TapCompany\LaravelSdk\Webhooks\WebhookController;

$middleware = config('tap.webhook.middleware', ['api']);

if (is_string($middleware)) {
    $middleware = array_filter(array_map('trim', explode(',', $middleware)));
}

Route::post(config('tap.webhook.path', 'tap/webhook'), WebhookController::class)
    ->middleware($middleware)
    ->name('tap.webhook');
