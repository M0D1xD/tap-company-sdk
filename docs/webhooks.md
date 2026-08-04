# Webhooks

When enabled, the SDK registers a `POST` route that validates Tap’s `hashstring` HMAC, dispatches Laravel events, and responds with `{"received": true}`.

Payload samples and the hashstring formula live in [`examples/webhooks.md`](examples/webhooks.md). Official Tap guide: [Webhook](https://developers.tap.company/docs/webhook.md).

## Enable and configure

You need `TAP_SECRET_KEY` for signature verification. Turn the package route on with:

```env
TAP_SECRET_KEY=sk_test_xxx
TAP_WEBHOOK_ENABLED=true
TAP_WEBHOOK_PATH=tap/webhook
TAP_WEBHOOK_MIDDLEWARE=api
TAP_WEBHOOK_HASH_HEADER=hashstring
```

| Env | Config key | Default |
|-----|------------|---------|
| `TAP_WEBHOOK_ENABLED` | `tap.webhook.enabled` | `false` |
| `TAP_WEBHOOK_PATH` | `tap.webhook.path` | `tap/webhook` |
| `TAP_WEBHOOK_MIDDLEWARE` | `tap.webhook.middleware` | `api` (comma-separated) |
| `TAP_WEBHOOK_HASH_HEADER` | `tap.webhook.header` | `hashstring` |

Or via published `config/tap.php` / `Tap::configure()` (call this in a service provider `boot()` so path and middleware apply before routes load):

```php
use TapCompany\LaravelSdk\Facades\Tap;

Tap::configure([
    'webhook' => [
        'enabled' => true,
        'path' => 'payments/tap/webhook',
        'middleware' => ['api'],
    ],
]);
```

## How the controller is registered

With `tap.webhook.enabled` set, `TapServiceProvider` loads [`routes/webhooks.php`](../routes/webhooks.php) after the app has booted. That registers:

- **Method / path:** `POST /{tap.webhook.path}` (default `/tap/webhook`)
- **Controller:** `TapCompany\LaravelSdk\Webhooks\WebhookController` (invokable)
- **Route name:** `tap.webhook`
- **Middleware:** from `tap.webhook.middleware`

Confirm it is registered:

```bash
php artisan route:list --name=tap.webhook
```

## Register the webhook with Tap

Tap must be able to reach your app over HTTPS. Set `APP_URL` to a public URL (use a tunnel such as ngrok, Expose, or Herd share for local development).

Pass the named route as `post.url` when creating a charge or authorization. Tap will POST status updates to that URL:

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
```

Same pattern for authorizations:

```php
$auth = Tap::authorizations()->create([
    // ...
    'post' => ['url' => route('tap.webhook')],
]);
```

`route('tap.webhook')` resolves to an absolute URL when `APP_URL` is correct. You can also force absolute form with `route('tap.webhook', absolute: true)`.

## Handle webhooks

After a valid signature, the controller always dispatches `TapWebhookReceived`, then a typed event based on `payload.object`:

| `object` | Event |
|----------|-------|
| *(always)* | `TapCompany\LaravelSdk\Events\TapWebhookReceived` |
| `charge` | `ChargeUpdated` |
| `authorize` | `AuthorizeUpdated` |
| `refund` | `RefundUpdated` |
| `invoice` | `InvoiceUpdated` |
| `payout` | `PayoutUpdated` |

Register a listener in `AppServiceProvider` or `EventServiceProvider`:

```php
use Illuminate\Support\Facades\Event;
use TapCompany\LaravelSdk\Events\ChargeUpdated;

Event::listen(ChargeUpdated::class, function (ChargeUpdated $event): void {
    $charge = $event->payload;
    // sync order from $charge->id(), $charge['status'], etc.
});
```

## Custom controller (optional)

Keep `TAP_WEBHOOK_ENABLED=false` and wire your own route. Verify the signature with the SDK; dispatch events yourself if you want the same event API:

```php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use TapCompany\LaravelSdk\Facades\Tap;

Route::post('/my/tap-webhook', function (Request $request) {
    $object = Tap::webhooks()->validateOrFail(
        $request->all(),
        $request->header('hashstring')
    );

    // handle $object or dispatch your own events

    return response()->json(['received' => true]);
})->middleware('api');
```

## Signature and responses

- Tap sends an HMAC-SHA256 signature in the header named by `tap.webhook.header` (default `hashstring`). The controller also accepts the capitalized form (`Hashstring`).
- **Valid:** `200` with `{"received": true}`
- **Invalid signature:** `400` with `{"message": "Invalid Tap webhook signature."}`

See [`examples/webhooks.md`](examples/webhooks.md) for the hashstring formula and sample payloads. Manual checks:

```php
Tap::webhooks()->validateOrFail($request->all(), $request->header('hashstring'));
// or: Tap::webhooks()->isValid($payload, $hash);
```

## Logging

With `TAP_LOGGING_ENABLED=true`, incoming webhooks are written to the configured Tap log channel (default `storage/logs/tap.log`) with path, status, and payload.
