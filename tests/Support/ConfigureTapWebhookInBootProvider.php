<?php

declare(strict_types=1);

namespace TapCompany\LaravelSdk\Tests\Support;

use Illuminate\Support\ServiceProvider;
use TapCompany\LaravelSdk\Facades\Tap;

class ConfigureTapWebhookInBootProvider extends ServiceProvider
{
    public function boot(): void
    {
        Tap::configure([
            'webhook' => [
                'enabled' => true,
                'path' => 'payments/tap/webhook',
                'middleware' => [],
            ],
        ]);
    }
}
