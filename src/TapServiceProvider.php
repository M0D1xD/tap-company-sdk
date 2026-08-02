<?php

declare(strict_types=1);

namespace TapCompany\LaravelSdk;

use Illuminate\Support\ServiceProvider;
use TapCompany\LaravelSdk\Http\TapHttpClient;
use TapCompany\LaravelSdk\Support\TapRequestLogger;
use TapCompany\LaravelSdk\Webhooks\SignatureValidator;

class TapServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/tap.php', 'tap');

        $this->app->singleton(TapRequestLogger::class);

        $this->app->singleton(TapHttpClient::class, function ($app): TapHttpClient {
            return new TapHttpClient(
                secretKey: (string) config('tap.secret_key', ''),
                baseUrl: (string) config('tap.base_url', 'https://api.tap.company/v2/'),
                timeout: (int) config('tap.timeout', 15),
                connectTimeout: (int) config('tap.connect_timeout', 5),
                retryTimes: (int) config('tap.retry.times', 2),
                retrySleep: (int) config('tap.retry.sleep', 200),
                logger: $app->make(TapRequestLogger::class),
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
        $this->registerTapLogChannel();

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/tap.php' => config_path('tap.php'),
            ], 'tap-config');
        }

        // Defer so AppServiceProvider::boot() / Tap::configure() can still change path/middleware.
        $this->app->booted(function (): void {
            if (config('tap.webhook.enabled')) {
                $this->loadRoutesFrom(__DIR__.'/../routes/webhooks.php');
            }
        });
    }

    protected function registerTapLogChannel(): void
    {
        if (config('tap.logging.channel', 'tap') !== 'tap') {
            return;
        }

        if (config('logging.channels.tap') !== null) {
            return;
        }

        config([
            'logging.channels.tap' => [
                'driver' => 'single',
                'path' => $this->resolveTapLogPath(),
                'level' => config('tap.logging.level', 'debug'),
                'replace_placeholders' => true,
            ],
        ]);
    }

    protected function resolveTapLogPath(): string
    {
        $path = (string) config('tap.logging.path', 'tap.log');

        if ($path === '') {
            $path = 'tap.log';
        }

        if (str_starts_with($path, '/') || preg_match('#^[A-Za-z]:[\\\\/]#', $path) === 1) {
            return $path;
        }

        return storage_path('logs/'.ltrim(str_replace('\\', '/', $path), '/'));
    }
}
