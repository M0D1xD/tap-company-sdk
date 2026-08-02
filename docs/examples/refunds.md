# Refunds

## Sources
- [Refunds overview](https://developers.tap.company/reference/refunds.md)
- [Create a Refund](https://developers.tap.company/reference/create-a-refund.md)

## Request

```json
{
  "charge_id": "chg_TS02A1720241236y4M42101629",
  "amount": 10,
  "currency": "KWD",
  "reason": "The product is out of stock",
  "post": {
    "url": "http://your_website.com/post_url"
  },
  "metadata": {
    "key": "value"
  },
  "reference": {
    "merchant": "1234"
  }
}
```

## Response

```json
{
  "id": "re_xxxx",
  "object": "refund",
  "api_version": "V2",
  "live_mode": false,
  "amount": 3.0,
  "charge_id": "chg_xxxx",
  "created": "1723481040882",
  "date": "1723481042085",
  "currency": "AED",
  "status": "REFUNDED",
  "reference": {
    "id": "xxxx",
    "gateway": "xxxx",
    "payment": "xxxx",
    "acquirer": "xxxx"
  },
  "response": {
    "code": "000",
    "message": "Refunded"
  },
  "post": {
    "status": "PENDING",
    "url": "http://your_website.com/post_url"
  },
  "acquirer": {
    "response": {
      "code": "000",
      "message": "Refunded"
    }
  },
  "gateway": {
    "response": {
      "code": "00",
      "message": "Approved"
    }
  },
  "method": "CREATE",
  "transaction": {
    "timezone": "UTC+03:00",
    "asynchronous": false,
    "amount": 3.0,
    "currency": "AED",
    "date": {
      "created": 1723481040882,
      "completed": 1723481042085
    }
  },
  "wallet": {
    "debit": false
  },
  "merchant": {
    "id": "xxxx"
  },
  "reverse_destination": false,
  "reason": "The product is out of stock"
}
```

## SDK usage

```php
use TapCompany\LaravelSdk\Facades\Tap;

$refund = Tap::refunds()->create([
    'charge_id' => 'chg_xxx',
    'amount' => 10,
    'currency' => 'KWD',
    'reason' => 'requested_by_customer',
]);
```

_Samples adapted from Tap’s public reference docs. Field names match the API; placeholder URLs/IDs are from Tap examples._
