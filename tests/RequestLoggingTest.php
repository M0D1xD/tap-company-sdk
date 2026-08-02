<?php

declare(strict_types=1);

namespace TapCompany\LaravelSdk\Tests;

use Illuminate\Log\Events\MessageLogged;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use TapCompany\LaravelSdk\Facades\Tap;

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
                && ! str_contains($encoded, 'sk_test_example')
                && ! str_contains($encoded, 'Bearer ');
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
}
