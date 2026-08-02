# Tap Company Laravel SDK

Full-platform Laravel SDK for the [Tap Payments API](https://developers.tap.company/reference/api-endpoint).

Supports Laravel **10–13** and PHP **8.1+**.

## Installation

```bash
composer require m0d1xd/tap-company-sdk
```

Publish the config:

```bash
php artisan vendor:publish --tag=tap-config
```

## Configuration

Copy [`.env.example`](.env.example) into your Laravel app `.env` (or merge the `TAP_*` keys):

```env
TAP_SECRET_KEY=sk_test_xxx
TAP_PUBLIC_KEY=pk_test_xxx
TAP_MERCHANT_ID=your_merchant_id
TAP_BASE_URL=https://api.tap.company/v2/
TAP_WEBHOOK_ENABLED=true
TAP_WEBHOOK_PATH=tap/webhook
```

Authentication uses `Authorization: Bearer {TAP_SECRET_KEY}` against Tap’s REST API.

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

Enable the package route with `TAP_WEBHOOK_ENABLED=true` (default path `/tap/webhook`).

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

This library is published to Packagist as `m0d1xd/tap-company-sdk`. Versions are cut automatically from Conventional Commits on `main` via GitHub Actions (release-please).

Full setup and day-to-day steps: **[docs/deploy.md](docs/deploy.md)**.

## License

MIT
