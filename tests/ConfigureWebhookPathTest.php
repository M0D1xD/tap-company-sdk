<?php

declare(strict_types=1);

namespace TapCompany\LaravelSdk\Tests;

use TapCompany\LaravelSdk\TapServiceProvider;
use TapCompany\LaravelSdk\Tests\Support\ConfigureTapWebhookInBootProvider;

class ConfigureWebhookPathTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            TapServiceProvider::class,
            ConfigureTapWebhookInBootProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        parent::defineEnvironment($app);

        $app['config']->set('tap.webhook.enabled', false);
        $app['config']->set('tap.webhook.path', 'tap/webhook');
    }

    public function test_configure_in_boot_applies_to_webhook_route(): void
    {
        $this->assertSame('payments/tap/webhook', config('tap.webhook.path'));
        $this->assertTrue(config('tap.webhook.enabled'));

        $this->postJson('/payments/tap/webhook', [
            'id' => 'chg_1',
            'object' => 'charge',
        ], [
            'hashstring' => 'invalid',
        ])->assertStatus(400);
    }
}
