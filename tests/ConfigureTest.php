<?php

declare(strict_types=1);

namespace TapCompany\LaravelSdk\Tests;

use TapCompany\LaravelSdk\Facades\Tap;
use TapCompany\LaravelSdk\Http\TapHttpClient;

class ConfigureTest extends TestCase
{
    public function test_configure_merges_values_into_tap_config(): void
    {
        Tap::configure([
            'secret_key' => 'sk_from_code',
            'merchant_id' => 'merchant_from_code',
            'webhook' => [
                'path' => 'custom/tap/hook',
            ],
        ]);

        $this->assertSame('sk_from_code', config('tap.secret_key'));
        $this->assertSame('merchant_from_code', config('tap.merchant_id'));
        $this->assertSame('custom/tap/hook', config('tap.webhook.path'));
        $this->assertTrue(config('tap.webhook.enabled'));
        $this->assertSame('pk_test_example', config('tap.public_key'));
    }

    public function test_configure_secret_key_is_used_by_http_client(): void
    {
        Tap::configure(['secret_key' => 'sk_runtime_secret']);

        $this->app->forgetInstance(TapHttpClient::class);

        $client = $this->app->make(TapHttpClient::class);

        $this->assertInstanceOf(TapHttpClient::class, $client);
        $this->assertSame('sk_runtime_secret', config('tap.secret_key'));
    }
}
