# Payouts

## Sources
- [Payout overview](https://developers.tap.company/reference/payout.md)
- [Retrieve a Payout](https://developers.tap.company/reference/retrieve-a-payout.md)
- [List All Payouts](https://developers.tap.company/reference/list-payouts.md)
- [Download Payouts](https://developers.tap.company/reference/download-payouts.md)

## Response (retrieve)

```json
{
  "object": "list",
  "live_mode": false,
  "api_version": "V2",
  "count": 1,
  "has_more": false,
  "payouts": [
    {
      "id": "payout_xxxx",
      "status": "PAID_OUT",
      "date": 1722781680000,
      "amount": 1000,
      "currency": "AED",
      "merchant_id": "599242",
      "wallet": {
        "id": "xxxx",
        "legacy_id": "xxxx",
        "country": "AE",
        "bank": {
          "id": "xx",
          "country": "AE",
          "name": "xxx",
          "swift": "xxxx",
          "beneficiary": {
            "name": "John Doe",
            "iban": "xxxxxx"
          }
        }
      },
      "settlements_available": true,
      "bank_reference": ""
    }
  ]
}
```

## Request (list / filters)

```json
{
  "period": {
    "date": {
      "from": "1722160390000",
      "to": "1722505990000"
    }
  },
  "merchants": [
    "599242"
  ],
  "payouts": {
    "payout_id": [
      "payout_xxxx"
    ]
  },
  "order_by": "date",
  "limit": 25
}
```

## Response (list)

```json
{
  "object": "list",
  "live_mode": false,
  "api_version": "V2",
  "count": 1,
  "has_more": false,
  "payouts": [
    {
      "id": "payout_xxxx",
      "status": "PAID_OUT",
      "date": 1722781680000,
      "amount": 300,
      "currency": "AED",
      "merchant_id": "599242",
      "wallet": {
        "id": "xxxx",
        "legacy_id": "xxxx",
        "country": "QA",
        "bank": {
          "id": "xx",
          "country": "xx",
          "name": "xxxx",
          "swift": "xxxx",
          "beneficiary": {
            "name": "xxxx",
            "iban": "xxxx"
          }
        }
      },
      "settlements_available": true,
      "bank_reference": ""
    },
    {
      "id": "payout_xxxx",
      "status": "PAID_OUT",
      "date": 1722781680000,
      "amount": 600,
      "currency": "AED",
      "merchant_id": "599242",
      "wallet": {
        "id": "xxxx",
        "legacy_id": "xxxx",
        "country": "QA",
        "bank": {
          "id": "xx",
          "country": "xx",
          "name": "xxxx",
          "swift": "xxxx",
          "beneficiary": {
            "name": "xxxx",
            "iban": "xxxx"
          }
        }
      },
      "settlements_available": true,
      "bank_reference": ""
    }
  ]
}
```

## Request (download)

```json
{
  "merchants": [
    "599424"
  ],
  "payouts": {
    "payout_id": [
      "payout_xxxx"
    ]
  }
}
```

## SDK usage

```php
use TapCompany\LaravelSdk\Facades\Tap;

$payout = Tap::payouts()->retrieve('pay_xxx');
$list = Tap::payouts()->list([/* filters */]);
$zip = Tap::payouts()->download([/* filters */]);
```

_Samples adapted from Tap’s public reference docs. Field names match the API; placeholder URLs/IDs are from Tap examples._
