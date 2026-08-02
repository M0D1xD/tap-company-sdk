# Connect

## Sources
- [Create a Connect URL](https://developers.tap.company/reference/create-a-connect-url.md)

## Request

```json
{
  "scope": "merchant",
  "data": [
    "operation",
    "brand",
    "entity",
    "merchant"
  ],
  "lead": {
    "id": "led_xxxx"
  },
  "board": {
    "editable": true,
    "display": true
  },
  "redirect": {
    "url": "http://example.com/redirectUrl"
  },
  "post": {
    "url": "http://example.com/postUrl"
  },
  "webhook": {
    "url": "http://example.com/webhookUrl"
  },
  "interface": {
    "direction": "rtl",
    "locale": "en",
    "edges": "curved"
  }
}
```

## Response

```json
{
  "id": "connect_xxxx",
  "created": 1722856025033,
  "api_version": "v3",
  "token": "xxxx",
  "operator": {
    "public_key": "pk_test_xxxx"
  },
  "scope": "auth",
  "data": [
    "operator",
    "brand",
    "entity",
    "merchant"
  ],
  "lead": {
    "id": "led_xxxx"
  },
  "board": {
    "display": true,
    "editable": true
  },
  "interface": {
    "locale": "en",
    "direction": "ltr",
    "edges": "curved"
  },
  "connect": {
    "url": "https://url-shortner.dev.tap.company/xxxx"
  },
  "redirect": {
    "url": "https://example.com/redirectUrl"
  },
  "post": {
    "url": "https://example.com/postUrl"
  },
  "lead_id": "led_xxxx",
  "public_key": "pk_test_xxxx"
}
```

## SDK usage

```php
use TapCompany\LaravelSdk\Facades\Tap;

$connect = Tap::connect()->createUrl('lead_xxx');
// redirect merchant to data_get($connect->toArray(), 'url') or equivalent field
```

_Samples adapted from Tap’s public reference docs. Field names match the API; placeholder URLs/IDs are from Tap examples._
