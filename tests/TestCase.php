<?php

declare(strict_types=1);

namespace TapCompany\LaravelSdk\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use TapCompany\LaravelSdk\TapServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            TapServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('tap.secret_key', 'sk_test_example');
        $app['config']->set('tap.public_key', 'pk_test_example');
        $app['config']->set('tap.merchant_id', '599424');
        $app['config']->set('tap.base_url', 'https://api.tap.company/v2/');
        $app['config']->set('tap.webhook.enabled', true);
        $app['config']->set('tap.webhook.path', 'tap/webhook');
        $app['config']->set('tap.webhook.middleware', []);
        $app['config']->set('tap.retry.times', 0);
    }
}
