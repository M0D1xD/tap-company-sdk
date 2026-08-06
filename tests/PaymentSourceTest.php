<?php

declare(strict_types=1);

namespace TapCompany\LaravelSdk\Tests;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use TapCompany\LaravelSdk\Data\PaymentSource;
use TapCompany\LaravelSdk\Enums\ChargeStatus;
use TapCompany\LaravelSdk\Enums\PaymentSourceId;
use TapCompany\LaravelSdk\Facades\Tap;

class PaymentSourceTest extends TestCase
{
    public function test_named_constructors_map_to_known_source_ids(): void
    {
        $this->assertSame(['id' => 'src_all'], PaymentSource::all()->toArray());
        $this->assertSame(['id' => 'src_card'], PaymentSource::card()->toArray());
        $this->assertSame(['id' => 'src_kw.knet'], PaymentSource::knet()->toArray());
        $this->assertSame(['id' => 'src_sa.mada'], PaymentSource::mada()->toArray());
        $this->assertSame(['id' => 'src_sa.stcpay'], PaymentSource::stcPay()->toArray());
        $this->assertSame(['id' => 'src_eg.fawry'], PaymentSource::fawry()->toArray());
        $this->assertSame(['id' => 'src_bh.benefit'], PaymentSource::benefit()->toArray());
        $this->assertSame(['id' => 'src_benefitpay'], PaymentSource::benefitPay()->toArray());
        $this->assertSame(['id' => 'src_qa.qpay'], PaymentSource::qpay()->toArray());
        $this->assertSame(['id' => 'src_omannet'], PaymentSource::omannet()->toArray());
        $this->assertSame(['id' => 'src_tabby.installement'], PaymentSource::tabbyInstallment()->toArray());
        $this->assertSame(['id' => 'src_apple_pay'], PaymentSource::applePay()->toArray());
        $this->assertSame(['id' => 'src_google_pay'], PaymentSource::googlePay()->toArray());
        $this->assertSame(['id' => 'src_samsung_pay'], PaymentSource::samsungPay()->toArray());
    }

    public function test_of_accepts_enum_or_raw_string(): void
    {
        $this->assertSame(
            PaymentSource::of(PaymentSourceId::KNET)->toArray(),
            PaymentSource::of('src_kw.knet')->toArray(),
        );
    }

    public function test_token_and_saved_card_and_encrypted_card_shapes(): void
    {
        $this->assertSame(['id' => 'tok_123'], PaymentSource::token('tok_123')->toArray());

        $this->assertSame(
            ['id' => 'card_123', 'on_file' => true],
            PaymentSource::savedCard('card_123')->toArray(),
        );

        $this->assertSame(
            ['id' => 'card_123', 'on_file' => false],
            PaymentSource::savedCard('card_123', false)->toArray(),
        );

        $this->assertSame(
            ['card' => 'encrypted-payload', 'on_file' => false],
            PaymentSource::encryptedCard('encrypted-payload')->toArray(),
        );
    }

    public function test_charge_status_helpers(): void
    {
        $this->assertTrue(ChargeStatus::CAPTURED->isSuccessful());
        $this->assertTrue(ChargeStatus::CAPTURED->isTerminal());
        $this->assertTrue(ChargeStatus::DECLINED->isTerminal());
        $this->assertFalse(ChargeStatus::DECLINED->isSuccessful());
        $this->assertFalse(ChargeStatus::INITIATED->isTerminal());
    }

    public function test_payment_source_serializes_correctly_inside_a_charge_payload(): void
    {
        Http::preventStrayRequests();
        Http::fake([
            'api.tap.company/v2/charges' => Http::response(['id' => 'chg_1', 'status' => 'INITIATED'], 200),
        ]);

        Tap::charges()->create([
            'amount' => 10,
            'currency' => 'KWD',
            'customer' => ['first_name' => 'John', 'email' => 'john@example.com'],
            'source' => PaymentSource::knet(),
            'redirect' => ['url' => 'https://example.com/redirect'],
        ]);

        Http::assertSent(function (Request $request): bool {
            $body = json_decode($request->body(), true);

            return $request->url() === 'https://api.tap.company/v2/charges'
                && $body['source'] === ['id' => 'src_kw.knet'];
        });
    }
}
