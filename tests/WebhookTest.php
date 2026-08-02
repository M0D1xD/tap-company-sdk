<?php

declare(strict_types=1);

namespace TapCompany\LaravelSdk\Tests;

use Illuminate\Support\Facades\Event;
use TapCompany\LaravelSdk\Events\ChargeUpdated;
use TapCompany\LaravelSdk\Events\TapWebhookReceived;
use TapCompany\LaravelSdk\Facades\Tap;
use TapCompany\LaravelSdk\Support\Currency;
use TapCompany\LaravelSdk\Webhooks\SignatureValidator;

class WebhookTest extends TestCase
{
    public function test_currency_formats_three_decimal_amounts(): void
    {
        $this->assertSame('1.000', Currency::formatAmount(1, 'KWD'));
        $this->assertSame('1.00', Currency::formatAmount(1, 'USD'));
        $this->assertSame('10.500', Currency::formatAmount(10.5, 'BHD'));
    }

    public function test_signature_validator_accepts_valid_hash(): void
    {
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

        $validator = new SignatureValidator('sk_test_example');
        $hash = $validator->compute($payload);

        $this->assertTrue($validator->isValid($payload, $hash));
        $this->assertFalse($validator->isValid($payload, 'bad-hash'));
    }

    public function test_webhook_endpoint_dispatches_events_for_valid_signature(): void
    {
        Event::fake([TapWebhookReceived::class, ChargeUpdated::class]);

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

        $response = $this->postJson('/tap/webhook', $payload, [
            'hashstring' => $hash,
        ]);

        $response->assertOk()->assertJson(['received' => true]);
        Event::assertDispatched(TapWebhookReceived::class);
        Event::assertDispatched(ChargeUpdated::class);
    }

    public function test_webhook_endpoint_rejects_invalid_signature(): void
    {
        $response = $this->postJson('/tap/webhook', [
            'id' => 'chg_1',
            'object' => 'charge',
            'amount' => 1,
            'currency' => 'SAR',
            'status' => 'CAPTURED',
        ], [
            'hashstring' => 'invalid',
        ]);

        $response->assertStatus(400);
    }
}
