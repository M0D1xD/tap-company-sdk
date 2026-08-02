<?php

declare(strict_types=1);

namespace TapCompany\LaravelSdk;

use Illuminate\Support\ServiceProvider;
use TapCompany\LaravelSdk\Http\TapHttpClient;
use TapCompany\LaravelSdk\Webhooks\SignatureValidator;

class TapServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/tap.php', 'tap');

        $this->app->singleton(TapHttpClient::class, function (): TapHttpClient {
            return new TapHttpClient(
                secretKey: (string) config('tap.secret_key', ''),
                baseUrl: (string) config('tap.base_url', 'https://api.tap.company/v2/'),
                timeout: (int) config('tap.timeout', 15),
                connectTimeout: (int) config('tap.connect_timeout', 5),
                retryTimes: (int) config('tap.retry.times', 2),
                retrySleep: (int) config('tap.retry.sleep', 200),
            );
        });

        $this->app->singleton(SignatureValidator::class, function (): SignatureValidator {
            return new SignatureValidator((string) config('tap.secret_key', ''));
        });

        $this->app->singleton(Tap::class, function ($app): Tap {
            return new Tap(
                $app->make(TapHttpClient::class),
                $app->make(SignatureValidator::class),
            );
        });
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/tap.php' => config_path('tap.php'),
            ], 'tap-config');
        }

        if (config('tap.webhook.enabled')) {
            $this->loadRoutesFrom(__DIR__.'/../routes/webhooks.php');
        }
    }
}
