<?php

declare(strict_types=1);

namespace TapCompany\LaravelSdk\Tests;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use TapCompany\LaravelSdk\Facades\Tap;

class ResourcesTest extends TestCase
{
    public function test_charges_retrieve_path(): void
    {
        Http::preventStrayRequests();
        Http::fake([
            'api.tap.company/v2/charges/chg_1' => Http::response(['id' => 'chg_1'], 200),
        ]);

        $charge = Tap::charges()->retrieve('chg_1');

        $this->assertSame('chg_1', $charge->id());
        Http::assertSent(fn (Request $request): bool => $request->method() === 'GET'
            && $request->url() === 'https://api.tap.company/v2/charges/chg_1');
    }

    public function test_authorizations_capture_uses_charges_endpoint(): void
    {
        Http::preventStrayRequests();
        Http::fake([
            'api.tap.company/v2/charges' => Http::response(['id' => 'chg_cap', 'status' => 'CAPTURED'], 200),
        ]);

        $charge = Tap::authorizations()->capture('auth_1', [
            'amount' => 10,
            'currency' => 'SAR',
            'customer' => ['id' => 'cus_1'],
        ]);

        $this->assertSame('CAPTURED', $charge['status']);
        Http::assertSent(fn (Request $request): bool => $request['source']['id'] === 'auth_1');
    }

    public function test_refunds_create_path(): void
    {
        Http::preventStrayRequests();
        Http::fake([
            'api.tap.company/v2/refunds' => Http::response(['id' => 're_1', 'object' => 'refund'], 200),
        ]);

        $refund = Tap::refunds()->create([
            'charge_id' => 'chg_1',
            'amount' => 1,
            'currency' => 'KWD',
            'reason' => 'requested_by_customer',
        ]);

        $this->assertSame('re_1', $refund->id);
    }

    public function test_customers_crud_paths(): void
    {
        Http::preventStrayRequests();
        Http::fake([
            'api.tap.company/v2/customers' => Http::response(['id' => 'cus_1'], 200),
            'api.tap.company/v2/customers/cus_1' => Http::sequence()
                ->push(['id' => 'cus_1', 'email' => 'a@b.com'], 200)
                ->push(['id' => 'cus_1', 'email' => 'c@d.com'], 200)
                ->push(['deleted' => true], 200),
        ]);

        $this->assertSame('cus_1', Tap::customers()->create(['first_name' => 'A'])->id());
        $this->assertSame('a@b.com', Tap::customers()->retrieve('cus_1')->email);
        $this->assertSame('c@d.com', Tap::customers()->update('cus_1', ['email' => 'c@d.com'])->email);
        $this->assertTrue((bool) Tap::customers()->delete('cus_1')['deleted']);
    }

    public function test_tokens_and_cards_paths(): void
    {
        Http::preventStrayRequests();
        Http::fake([
            'api.tap.company/v2/tokens' => Http::response(['id' => 'tok_1'], 200),
            'api.tap.company/v2/card/cus_1/card_1' => Http::response(['id' => 'card_1'], 200),
            'api.tap.company/v2/card/verify' => Http::response(['status' => 'VALID'], 200),
        ]);

        $this->assertSame('tok_1', Tap::tokens()->create(['card' => ['number' => '4242']])->id());
        $this->assertSame('card_1', Tap::cards()->retrieve('cus_1', 'card_1')->id());
        $this->assertSame('VALID', Tap::cards()->verify(['source' => ['id' => 'tok_1']])['status']);
    }

    public function test_invoices_and_intents_paths(): void
    {
        Http::preventStrayRequests();
        Http::fake([
            'api.tap.company/v2/invoices' => Http::response(['id' => 'inv_1'], 200),
            'api.tap.company/v2/intent' => Http::response(['id' => 'int_1'], 200),
            'api.tap.company/v2/intent/int_1/cancel' => Http::response(['id' => 'int_1', 'status' => 'CANCELLED'], 200),
        ]);

        $this->assertSame('inv_1', Tap::invoices()->create(['amount' => 5, 'currency' => 'KWD'])->id());
        $this->assertSame('int_1', Tap::intents()->create(['amount' => 5, 'currency' => 'KWD'])->id());
        $this->assertSame('CANCELLED', Tap::intents()->cancel('int_1')['status']);
    }

    public function test_marketplace_resources_paths(): void
    {
        Http::preventStrayRequests();
        Http::fake([
            'api.tap.company/v2/lead' => Http::response(['id' => 'lead_1'], 200),
            'api.tap.company/v2/connect' => Http::response(['url' => 'https://connect.tap.company/x'], 200),
            'api.tap.company/v2/business' => Http::response(['id' => 'biz_1'], 200),
            'api.tap.company/v2/merchant' => Http::response(['id' => 'mer_1'], 200),
            'api.tap.company/v2/destination/dest_1' => Http::response(['id' => 'dest_1'], 200),
            'api.tap.company/v2/payouts/pay_1' => Http::response(['id' => 'pay_1'], 200),
        ]);

        $this->assertSame('lead_1', Tap::leads()->create(['email' => 'a@b.com'])->id());
        $this->assertSame('https://connect.tap.company/x', Tap::connect()->createUrl('lead_1')['url']);
        $this->assertSame('biz_1', Tap::businesses()->create(['name' => 'Acme'])->id());
        $this->assertSame('mer_1', Tap::merchants()->create(['business_id' => 'biz_1'])->id());
        $this->assertSame('dest_1', Tap::destinations()->retrieve('dest_1')->id());
        $this->assertSame('pay_1', Tap::payouts()->retrieve('pay_1')->id());
    }

    public function test_download_returns_raw_body(): void
    {
        Http::preventStrayRequests();
        Http::fake([
            'api.tap.company/v2/charges/download' => Http::response("id,amount\nchg_1,1.000", 200, [
                'Content-Type' => 'text/csv',
            ]),
            'api.tap.company/v2/disputes/download' => Http::response('zip-bytes', 200),
        ]);

        $this->assertStringContainsString('chg_1', Tap::charges()->download(['period' => ['date' => ['from' => 1]]]));
        $this->assertSame('zip-bytes', Tap::disputes()->download([]));
    }
}
