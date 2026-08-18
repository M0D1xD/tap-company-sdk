<?php

declare(strict_types=1);

namespace TapCompany\LaravelSdk\Tests;

use Illuminate\Log\Events\MessageLogged;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use TapCompany\LaravelSdk\Data\PaymentSource;
use TapCompany\LaravelSdk\Facades\Tap;
use TapCompany\LaravelSdk\Http\TapHttpClient;
use TapCompany\LaravelSdk\Support\TapRequestLogger;
use TapCompany\LaravelSdk\Tap as TapSdk;

class RequestLoggingTest extends TestCase
{
    public function test_it_does_not_log_when_logging_is_disabled(): void
    {
        Event::fake([MessageLogged::class]);
        config(['tap.logging.enabled' => false]);

        Http::preventStrayRequests();
        Http::fake([
            'api.tap.company/v2/charges*' => Http::response(['id' => 'chg_1'], 200),
        ]);

        Tap::charges()->retrieve('chg_1');

        Event::assertNotDispatched(MessageLogged::class);
    }

    public function test_it_logs_outgoing_requests_with_payloads_when_enabled(): void
    {
        Event::fake([MessageLogged::class]);
        config([
            'tap.logging.enabled' => true,
            'tap.logging.channel' => 'tap',
            'tap.logging.log_payloads' => true,
        ]);

        Http::preventStrayRequests();
        Http::fake([
            'api.tap.company/v2/charges*' => Http::response([
                'id' => 'chg_1',
                'object' => 'charge',
                'status' => 'INITIATED',
            ], 200),
        ]);

        Tap::charges()->create([
            'amount' => 1,
            'currency' => 'KWD',
            'customer' => ['first_name' => 'Test'],
            'source' => ['id' => 'src_all'],
            'redirect' => ['url' => 'https://example.com/callback'],
            'secret_key' => 'sk_should_be_redacted',
        ]);

        Event::assertDispatched(MessageLogged::class, function (MessageLogged $log): bool {
            if ($log->message !== 'Tap outgoing request') {
                return false;
            }

            $context = $log->context;
            $encoded = json_encode($context) ?: '';

            return ($context['direction'] ?? null) === 'outgoing'
                && ($context['method'] ?? null) === 'POST'
                && str_contains((string) ($context['url'] ?? ''), '/charges')
                && ($context['status'] ?? null) === 200
                && ($context['request']['amount'] ?? null) === 1
                && ($context['request']['secret_key'] ?? null) === '[redacted]'
                && ($context['response']['id'] ?? null) === 'chg_1'
                && $this->headerValue($context['headers'] ?? [], 'Authorization') === '[redacted]'
                && ! str_contains($encoded, 'sk_test_example')
                && ! str_contains($encoded, 'Bearer ');
        });
    }

    public function test_it_logs_payment_source_as_wire_json(): void
    {
        Event::fake([MessageLogged::class]);
        config([
            'tap.logging.enabled' => true,
            'tap.logging.log_payloads' => true,
        ]);

        Http::preventStrayRequests();
        Http::fake([
            'api.tap.company/v2/charges*' => Http::response(['id' => 'chg_1'], 200),
        ]);

        Tap::charges()->create([
            'amount' => 1,
            'currency' => 'KWD',
            'source' => PaymentSource::knet(),
        ]);

        Event::assertDispatched(MessageLogged::class, function (MessageLogged $log): bool {
            if ($log->message !== 'Tap outgoing request') {
                return false;
            }

            $context = $log->context;

            return ($context['request']['source'] ?? null) === ['id' => 'src_kw.knet']
                && ($context['request']['merchant']['id'] ?? null) === '599424';
        });
    }

    public function test_it_logs_idempotency_key_header(): void
    {
        Event::fake([MessageLogged::class]);
        config(['tap.logging.enabled' => true]);

        Http::preventStrayRequests();
        Http::fake([
            'api.tap.company/v2/charges*' => Http::response(['id' => 'chg_1'], 200),
        ]);

        Tap::charges()->create(['amount' => 1, 'currency' => 'KWD'], 'order-100');

        Event::assertDispatched(MessageLogged::class, function (MessageLogged $log): bool {
            if ($log->message !== 'Tap outgoing request') {
                return false;
            }

            return $this->headerValue($log->context['headers'] ?? [], 'Idempotency-Key') === 'order-100';
        });
    }

    public function test_it_omits_payloads_when_log_payloads_is_disabled(): void
    {
        Event::fake([MessageLogged::class]);
        config([
            'tap.logging.enabled' => true,
            'tap.logging.log_payloads' => false,
        ]);

        Http::preventStrayRequests();
        Http::fake([
            'api.tap.company/v2/charges*' => Http::response(['id' => 'chg_1'], 200),
        ]);

        Tap::charges()->retrieve('chg_1');

        Event::assertDispatched(MessageLogged::class, function (MessageLogged $log): bool {
            if ($log->message !== 'Tap outgoing request') {
                return false;
            }

            $context = $log->context;

            return ($context['direction'] ?? null) === 'outgoing'
                && ($context['status'] ?? null) === 200
                && $this->headerValue($context['headers'] ?? [], 'Authorization') === '[redacted]'
                && ! array_key_exists('request', $context)
                && ! array_key_exists('response', $context);
        });
    }

    public function test_it_logs_incoming_webhooks_when_enabled(): void
    {
        Event::fake([MessageLogged::class]);
        config(['tap.logging.enabled' => true]);

        $payload = [
            'id' => 'chg_1',
            'object' => 'charge',
            'amount' => 1,
            'currency' => 'SAR',
            'status' => 'CAPTURED',
            'reference' => [
                'gateway' => 'gw_1',
                'payment' => 'pay_1',
            ],
            'transaction' => [
                'created' => '1698392202943',
            ],
        ];

        $hash = Tap::webhooks()->compute($payload);

        $this->postJson('/tap/webhook', $payload, [
            'hashstring' => $hash,
        ])->assertOk();

        Event::assertDispatched(MessageLogged::class, function (MessageLogged $log) use ($payload): bool {
            if ($log->message !== 'Tap incoming webhook') {
                return false;
            }

            $context = $log->context;

            return ($context['direction'] ?? null) === 'incoming'
                && ($context['path'] ?? null) === '/tap/webhook'
                && ($context['status'] ?? null) === 200
                && ($context['payload']['id'] ?? null) === $payload['id'];
        });
    }

    public function test_it_registers_tap_log_channel_pointing_at_tap_log(): void
    {
        $channel = config('logging.channels.tap');

        $this->assertIsArray($channel);
        $this->assertSame('single', $channel['driver']);
        $this->assertSame(storage_path('logs/tap.log'), $channel['path']);
    }

    public function test_it_dumps_outgoing_requests_when_debug_dump_is_enabled(): void
    {
        $dumps = [];
        $this->swapLogger(new TapRequestLogger(
            dumper: function (array $snapshot) use (&$dumps): void {
                $dumps[] = $snapshot;
            },
            terminator: function (): never {
                throw new RuntimeException('should not halt');
            },
        ));

        config(['tap.debug.dump' => true]);

        Http::preventStrayRequests();
        Http::fake([
            'api.tap.company/v2/charges*' => Http::response(['id' => 'chg_1'], 200),
        ]);

        Tap::charges()->create([
            'amount' => 1,
            'currency' => 'KWD',
            'source' => PaymentSource::knet(),
        ]);

        $this->assertCount(1, $dumps);
        $this->assertSame('POST', $dumps[0]['method']);
        $this->assertSame(['id' => 'src_kw.knet'], $dumps[0]['body']['source']);
        $this->assertSame('[redacted]', $this->headerValue($dumps[0]['headers'], 'Authorization'));
        $this->assertSame('599424', $dumps[0]['body']['merchant']['id']);
        Http::assertSentCount(1);
    }

    public function test_dump_enables_inspection_without_debug_config(): void
    {
        $dumps = [];
        $this->swapLogger(new TapRequestLogger(
            dumper: function (array $snapshot) use (&$dumps): void {
                $dumps[] = $snapshot;
            },
        ));

        config(['tap.debug.dump' => false]);

        Http::preventStrayRequests();
        Http::fake([
            'api.tap.company/v2/charges*' => Http::response(['id' => 'chg_1'], 200),
        ]);

        Tap::dump()->charges()->create([
            'amount' => 1,
            'currency' => 'KWD',
        ]);

        $this->assertCount(1, $dumps);
        $this->assertSame(1, $dumps[0]['body']['amount']);
        Http::assertSentCount(1);
    }

    public function test_dd_halts_before_sending(): void
    {
        $dumps = [];
        $this->swapLogger(new TapRequestLogger(
            dumper: function (array $snapshot) use (&$dumps): void {
                $dumps[] = $snapshot;
            },
            terminator: function (): never {
                throw new RuntimeException('tap dd halt');
            },
        ));

        Http::preventStrayRequests();
        Http::fake([
            'api.tap.company/v2/charges*' => Http::response(['id' => 'chg_1'], 200),
        ]);

        try {
            Tap::dd()->charges()->create([
                'amount' => 1,
                'currency' => 'KWD',
            ]);
            $this->fail('Expected Tap::dd() to halt before sending.');
        } catch (RuntimeException $exception) {
            $this->assertSame('tap dd halt', $exception->getMessage());
        }

        $this->assertCount(1, $dumps);
        $this->assertSame('POST', $dumps[0]['method']);
        Http::assertNothingSent();
    }

    protected function swapLogger(TapRequestLogger $logger): void
    {
        $this->app->instance(TapRequestLogger::class, $logger);
        $this->app->forgetInstance(TapHttpClient::class);
        $this->app->forgetInstance(TapSdk::class);
        Tap::clearResolvedInstance(TapSdk::class);
    }

    /**
     * @param  array<string, string>  $headers
     */
    protected function headerValue(array $headers, string $name): ?string
    {
        foreach ($headers as $header => $value) {
            if (strcasecmp($header, $name) === 0) {
                return $value;
            }
        }

        return null;
    }
}
