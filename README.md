# Tap Company Laravel SDK

Full-platform Laravel SDK for the [Tap Payments API](https://developers.tap.company/reference/api-endpoint).

Supports Laravel **12–13** and PHP **8.2+**.

> **Disclaimer:** This package is **not** an official product of [Tap Company](https://www.tap.company) / Tap Payments, and is **not** affiliated with, endorsed by, or maintained by their developers. It is a personal, vibe-coded Laravel SDK built independently to provide a nicer developer experience on top of the public [Tap Payments API](https://developers.tap.company/reference/api-endpoint). Use at your own risk; API behavior and docs may change without notice.

## Installation

```bash
composer require m0d1xd/tap-company-sdk
```

Publish the config:

```bash
php artisan vendor:publish --tag=tap-config
```

## Configuration

You can supply any Tap setting via **`.env`**, the **published config file**, or **`Tap::configure()`** in code. The SDK always reads `config('tap.*')` — `env()` is only a convenience default in the config file.

### Environment variables

Copy [`.env.example`](.env.example) into your Laravel app `.env` (or merge the `TAP_*` keys):

```env
TAP_SECRET_KEY=sk_test_xxx
TAP_PUBLIC_KEY=pk_test_xxx
TAP_MERCHANT_ID=your_merchant_id
TAP_BASE_URL=https://api.tap.company/v2/
TAP_WEBHOOK_ENABLED=true
TAP_WEBHOOK_PATH=tap/webhook
TAP_LOGGING_ENABLED=true
```

### Request logging

Set `TAP_LOGGING_ENABLED=true` to write every outgoing API call and incoming webhook (method, URL/path, status, and payloads) to `storage/logs/tap.log` via the dedicated `tap` log channel. Disable bodies with `TAP_LOGGING_PAYLOADS=false`, or point `TAP_LOGGING_CHANNEL` at another Laravel channel (e.g. `stack`) if you prefer.

### Published config

After `php artisan vendor:publish --tag=tap-config`, edit `config/tap.php` and set values directly if you prefer not to use `.env`:

```php
'secret_key' => 'sk_test_xxx',
'webhook' => [
    'enabled' => true,
    'path' => 'payments/tap/webhook',
],
```

### Programmatic configuration

Set any option at runtime from a service provider (before the Tap client is first resolved):

```php
use TapCompany\LaravelSdk\Facades\Tap;

public function boot(): void
{
    Tap::configure([
        'secret_key' => $this->resolveTapSecret(),
        'merchant_id' => 'merchant_xxx',
        'webhook' => [
            'enabled' => true,
            'path' => 'payments/tap/webhook',
        ],
    ]);
}
```

Authentication uses `Authorization: Bearer {secret_key}` against Tap’s REST API.

## Object examples

Full Tap request/response JSON samples (sourced from the official reference `.md` pages) live in [`docs/examples/`](docs/examples/README.md) — charges, authorizations, refunds, customers, tokens, cards, invoices, intents, payouts, marketplace leads/businesses/merchants/destinations, files, disputes, connect, and webhooks.

## Quick start — charge

```php
use TapCompany\LaravelSdk\Facades\Tap;

$charge = Tap::charges()->create([
    'amount' => 10,
    'currency' => 'KWD',
    'customer' => [
        'first_name' => 'John',
        'email' => 'john@example.com',
        'phone' => [
            'country_code' => '965',
            'number' => '50000000',
        ],
    ],
    'source' => ['id' => 'src_all'],
    'redirect' => ['url' => route('checkout.callback')],
    'post' => ['url' => route('tap.webhook')],
]);

return redirect()->away(data_get($charge->toArray(), 'transaction.url'));
```

Optional idempotency:

```php
Tap::charges()->create($payload, idempotencyKey: $order->id);
```

### Typed payment sources

`TapCompany\LaravelSdk\Data\PaymentSource` gives you autocomplete instead of raw `['id' => 'src_...']` arrays. It implements `Arrayable`/`JsonSerializable`, so an instance can be dropped straight into the `source` key of any charge/authorization payload:

```php
use TapCompany\LaravelSdk\Data\PaymentSource;

Tap::charges()->create([
    'amount' => 10,
    'currency' => 'KWD',
    'customer' => ['first_name' => 'John', 'email' => 'john@example.com'],
    'source' => PaymentSource::knet(), // or ->all(), ->card(), ->applePay(), ->mada(), ->fawry(), ->stcPay(), ...
    'redirect' => ['url' => route('checkout.callback')],
]);

// A previously saved card or token also has a typed constructor:
PaymentSource::savedCard('card_xxx');
PaymentSource::token('tok_xxx');
```

Available named constructors: `card()`, `all()`, `applePay()`, `googlePay()`, `samsungPay()`, `knet()`, `mada()`, `stcPay()`, `fawry()`, `benefit()`, `benefitPay()`, `qpay()`, `omannet()`, `tabbyInstallment()`, `token(string $id)`, `savedCard(string $id, bool $onFile = true)`, `encryptedCard(string $encrypted, bool $onFile = false)`, or `of(PaymentSourceId|string $id)` for anything else. The full backing list of ids lives in the `TapCompany\LaravelSdk\Enums\PaymentSourceId` enum.

`TapCompany\LaravelSdk\Enums\ChargeStatus` mirrors the `status` values Tap returns on a charge (`CAPTURED`, `DECLINED`, `INITIATED`, ...) with `isSuccessful()`/`isTerminal()` helpers:

```php
use TapCompany\LaravelSdk\Enums\ChargeStatus;

$status = ChargeStatus::from($charge['status']);

if ($status->isSuccessful()) {
    // ...
}
```

## Authorize and capture

```php
$auth = Tap::authorizations()->create([
    'amount' => 25,
    'currency' => 'SAR',
    'customer' => ['first_name' => 'Jane', 'email' => 'jane@example.com'],
    'source' => ['id' => 'src_card'],
    'redirect' => ['url' => route('checkout.callback')],
]);

// Later: capture authorized funds
$charge = Tap::authorizations()->capture($auth->id(), [
    'amount' => 25,
    'currency' => 'SAR',
    'customer' => ['id' => $auth->customer['id']],
]);
```

## Refunds

```php
$refund = Tap::refunds()->create([
    'charge_id' => 'chg_xxx',
    'amount' => 10,
    'currency' => 'KWD',
    'reason' => 'requested_by_customer',
]);
```

## Customers, tokens, and cards

```php
$customer = Tap::customers()->create([
    'first_name' => 'John',
    'email' => 'john@example.com',
]);

$token = Tap::tokens()->createFromSavedCard([
    'saved_card' => [
        'card_id' => 'card_xxx',
        'customer_id' => $customer->id(),
    ],
]);

$cards = Tap::cards()->list($customer->id());
```

Also available: `createEncrypted()`, `createApplePay()`, `createSamsungPay()`, `createNetworkToken()`, `cards()->verify()`.

## Invoices and intents (POS)

```php
$invoice = Tap::invoices()->create([/* ... */]);

$intent = Tap::intents()->create([/* POS payload */]);
Tap::intents()->cancel($intent->id());
```

## Marketplace / Connect

```php
$lead = Tap::leads()->create([/* merchant lead */]);
$connect = Tap::connect()->createUrl($lead->id());

$business = Tap::businesses()->create([/* ... */]);
$merchant = Tap::merchants()->create(['business_id' => $business->id()]);
$destination = Tap::destinations()->retrieve('dest_xxx');
```

Retailer helpers: `leads()->createRetailer()`, `updateRetailer()`, `convertToRetailer()`.

## Files, payouts, disputes

```php
Tap::files()->create(
    ['purpose' => 'identity_document'],
    ['path' => storage_path('app/kyc.pdf'), 'name' => 'file', 'filename' => 'kyc.pdf'],
);

$payout = Tap::payouts()->retrieve('pay_xxx');
$csv = Tap::charges()->download(['period' => [/* ... */]]);
$zip = Tap::payouts()->download([/* ... */]);
$disputes = Tap::disputes()->download([/* ... */]);
```

## Webhooks

Enable the package route with `TAP_WEBHOOK_ENABLED=true` (default path `/tap/webhook`). Full setup — configuration, registering `post.url` with Tap, events, and a custom controller — is in [`docs/webhooks.md`](docs/webhooks.md).

The controller validates Tap’s `hashstring` HMAC and dispatches:

- `TapCompany\LaravelSdk\Events\TapWebhookReceived`
- `ChargeUpdated` / `AuthorizeUpdated` / `RefundUpdated` / `InvoiceUpdated` / `PayoutUpdated`

```php
use TapCompany\LaravelSdk\Events\ChargeUpdated;

Event::listen(ChargeUpdated::class, function (ChargeUpdated $event): void {
    $charge = $event->payload;
    // sync order from $charge->id(), $charge['status'], etc.
});
```

Manual verification:

```php
Tap::webhooks()->validateOrFail($request->all(), $request->header('hashstring'));
```

## Available resources

| Accessor | Purpose |
|----------|---------|
| `Tap::charges()` | Create, retrieve, update, list, download |
| `Tap::authorizations()` | Authorize, update, capture, download |
| `Tap::refunds()` | Refunds |
| `Tap::customers()` | Customer CRUD |
| `Tap::tokens()` | Payment tokens |
| `Tap::cards()` | Saved cards + verify |
| `Tap::invoices()` | Invoices |
| `Tap::intents()` | POS intents |
| `Tap::payouts()` | Settlements |
| `Tap::leads()` | Marketplace leads |
| `Tap::connect()` | Connect onboarding URL |
| `Tap::businesses()` | Marketplace businesses |
| `Tap::merchants()` | Sub-merchants |
| `Tap::destinations()` | Split destinations |
| `Tap::files()` | File uploads |
| `Tap::disputes()` | Dispute downloads |

## Testing

```bash
composer install
composer test
```

Use [Tap test cards](https://developers.tap.company/reference/testing-cards.md) with `sk_test_*` keys.

## Contributing

See [CONTRIBUTING.md](CONTRIBUTING.md) for Conventional Commits, PR titles, and the test workflow.

## Releasing / Deploy

This library is published to Packagist as `m0d1xd/tap-company-sdk`. Versions are cut automatically from Conventional Commits on `main` via release-please; each GitHub Release triggers a Packagist sync workflow.

**Integrate Packagist with GitHub Actions:** add `PACKAGIST_USERNAME` and `PACKAGIST_TOKEN` secrets, then follow **[docs/deploy.md](docs/deploy.md)**.

## License

MIT
