<?php

declare(strict_types=1);

namespace TapCompany\LaravelSdk\Tests;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use TapCompany\LaravelSdk\Exceptions\ApiRequestException;
use TapCompany\LaravelSdk\Facades\Tap;

class TapHttpClientTest extends TestCase
{
    public function test_it_sends_bearer_authorization_header(): void
    {
        Http::preventStrayRequests();
        Http::fake([
            'api.tap.company/v2/charges*' => Http::response(['id' => 'chg_1', 'object' => 'charge'], 200),
        ]);

        Tap::charges()->create([
            'amount' => 1,
            'currency' => 'KWD',
            'customer' => ['first_name' => 'Test'],
            'source' => ['id' => 'src_all'],
            'redirect' => ['url' => 'https://example.com/callback'],
        ]);

        Http::assertSent(function (Request $request): bool {
            return $request->hasHeader('Authorization', 'Bearer sk_test_example')
                && $request->url() === 'https://api.tap.company/v2/charges'
                && $request['merchant']['id'] === '599424';
        });
    }

    public function test_it_sends_idempotency_key_when_provided(): void
    {
        Http::preventStrayRequests();
        Http::fake([
            'api.tap.company/v2/charges*' => Http::response(['id' => 'chg_2'], 200),
        ]);

        Tap::charges()->create(['amount' => 1, 'currency' => 'KWD'], 'order-100');

        Http::assertSent(function (Request $request): bool {
            return $request->hasHeader('Idempotency-Key', 'order-100');
        });
    }

    public function test_it_throws_api_request_exception_on_failure(): void
    {
        Http::preventStrayRequests();
        Http::fake([
            'api.tap.company/v2/charges/*' => Http::response([
                'errors' => [['description' => 'Charge not found']],
            ], 404),
        ]);

        $this->expectException(ApiRequestException::class);
        $this->expectExceptionMessage('Charge not found');

        Tap::charges()->retrieve('chg_missing');
    }
}
