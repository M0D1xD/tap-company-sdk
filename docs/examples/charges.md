# Charges

## Sources
- [Charges overview](https://developers.tap.company/reference/charges.md)
- [Create a Charge](https://developers.tap.company/reference/create-a-charge.md)

## Request

From the Charges overview sample (`save_card` normalized to `false`; Tap docs show `false/true`).

```json
{
  "amount": 10,
  "currency": "AED",
  "threeDSecure": true,
  "save_card": false,
  "customer_initiated": true,
  "description": "Test Description",
  "statement_descriptor": "Sample",
  "metadata": {
    "udf1": "test 1",
    "udf2": "test 2"
  },
  "reference": {
    "transaction": "txn_0001",
    "order": "ord_0001"
  },
  "receipt": {
    "email": true,
    "sms": false
  },
  "customer": {
    "first_name": "Waleed",
    "last_name": "Asghar",
    "email": "w.asghar@tap.company",
    "phone": {
      "country_code": "971",
      "number": "586275033"
    }
  },
  "merchant": {
    "id": ""
  },
  "source": {
    "id": "src_all"
  },
  "post": {
    "url": "http://your_website.com/post_url"
  },
  "redirect": {
    "url": "http://your_website.com/redirect_url"
  }
}
```

## Response (CAPTURED)

```json
{
  "id": "chg_TS03A2220231551Ha3j1408926",
  "object": "charge",
  "live_mode": false,
  "customer_initiated": true,
  "api_version": "V2",
  "method": "GET",
  "status": "CAPTURED",
  "amount": 10.0,
  "currency": "AED",
  "threeDSecure": true,
  "card_threeDSecure": false,
  "save_card": true,
  "merchant_id": "",
  "product": "GOSELL",
  "statement_descriptor": "Sample",
  "description": "Test Description",
  "metadata": {
    "udf1": "test 1",
    "udf2": "test 2"
  },
  "order": {
    "id": "ord_lWdQ29231251p2pQ14NO7c311"
  },
  "transaction": {
    "authorization_id": "030982",
    "timezone": "UTC+03:00",
    "created": "1692028310505",
    "expiry": {
      "period": 30,
      "type": "MINUTE"
    },
    "asynchronous": false,
    "amount": 10.0,
    "currency": "AED"
  },
  "reference": {
    "track": "tck_TS01A2220231551t1K31408958",
    "payment": "2214231551089588145",
    "gateway": "123456789012345",
    "acquirer": "322612030982",
    "transaction": "txn_0001",
    "order": "ord_0001"
  },
  "response": {
    "code": "000",
    "message": "Captured"
  },
  "security": {
    "threeDSecure": {
      "id": "3ds_TS06A5020231551j5JH1408505",
      "status": "Y"
    }
  },
  "acquirer": {
    "response": {
      "code": "00",
      "message": "Approved"
    }
  },
  "gateway": {
    "response": {
      "code": "0",
      "message": "Transaction Approved"
    }
  },
  "card": {
    "id": "card_NOSc48231251fiLc14rl7u285",
    "object": "card",
    "first_six": "411111",
    "scheme": "VISA",
    "brand": "VISA",
    "last_four": "1111"
  },
  "receipt": {
    "id": "202314231551081692",
    "email": true,
    "sms": false
  },
  "customer": {
    "id": "cus_TS06A5220231551Rl7y1408020",
    "first_name": "Waleed",
    "last_name": "Asghar",
    "email": "w.asghar@tap.company",
    "phone": {
      "country_code": "971",
      "number": "586275033"
    }
  },
  "merchant": {
    "country": "AE",
    "currency": "AED",
    "id": "14583257"
  },
  "source": {
    "object": "token",
    "type": "CARD_NOT_PRESENT",
    "payment_type": "CREDIT",
    "payment_method": "VISA",
    "channel": "INTERNET",
    "id": "tok_mgtn48231251SX3414YZ7l282"
  },
  "redirect": {
    "status": "SUCCESS",
    "url": "http://your_website.com/redirect_url"
  },
  "post": {
    "status": "ERROR",
    "url": "http://your_website.com/post_url"
  },
  "activities": [
    {
      "id": "activity_TS07A5220231551Mj841408864",
      "object": "activity",
      "created": 1692028310505,
      "status": "INITIATED",
      "currency": "AED",
      "amount": 10.0,
      "remarks": "charge - created"
    },
    {
      "id": "activity_TS05A1020231552Oe1m1408270",
      "object": "activity",
      "created": 1692028330270,
      "status": "CAPTURED",
      "currency": "AED",
      "amount": 10.0,
      "remarks": "charge - captured"
    }
  ],
  "auto_reversed": false,
  "payment_agreement": {
    "id": "payment_agreement_h5Rp522312517fHg14Vg7a661",
    "type": "UNSCHEDULED",
    "total_payments_count": 1,
    "contract": {
      "id": "card_NOSc48231251fiLc14rl7u285",
      "customer_id": "cus_TS06A5220231551Rl7y1408020",
      "type": "SAVED_CARD"
    },
    "metadata": {
      "txn_type": "CHARGE",
      "txn_id": "chg_TS03A2220231551Ha3j1408926",
      "terminal_id": "ter_p3F64020211320j6XQ1002702"
    }
  }
}
```

## Variant — marketplace destinations

```json
{
  "destinations": {
    "destination": [
      {
        "id": "480593",
        "amount": 2,
        "currency": "AED"
      },
      {
        "id": "486374",
        "amount": 3,
        "currency": "AED"
      }
    ]
  }
}
```

## Variant — custom expiry

```json
{
  "transaction": {
    "expiry": {
      "period": 60,
      "type": "MINUTE"
    }
  }
}
```

## SDK usage

```php
use TapCompany\LaravelSdk\Facades\Tap;

$charge = Tap::charges()->create([
    'amount' => 10,
    'currency' => 'AED',
    'threeDSecure' => true,
    'save_card' => false,
    'customer_initiated' => true,
    'description' => 'Test Description',
    'customer' => [
        'first_name' => 'Waleed',
        'last_name' => 'Asghar',
        'email' => 'w.asghar@tap.company',
        'phone' => ['country_code' => '971', 'number' => '586275033'],
    ],
    'source' => ['id' => 'src_all'],
    'redirect' => ['url' => 'https://example.com/redirect'],
    'post' => ['url' => 'https://example.com/post'],
]);

return redirect()->away(data_get($charge->toArray(), 'transaction.url'));
```

### Typed payment sources

`source` also accepts a `TapCompany\LaravelSdk\Data\PaymentSource` instance instead of a raw array, for IDE autocomplete over the valid `source.id` values:

```php
use TapCompany\LaravelSdk\Data\PaymentSource;
use TapCompany\LaravelSdk\Enums\ChargeStatus;
use TapCompany\LaravelSdk\Facades\Tap;

$charge = Tap::charges()->create([
    'amount' => 10,
    'currency' => 'AED',
    'customer' => [
        'first_name' => 'Waleed',
        'email' => 'w.asghar@tap.company',
    ],
    'source' => PaymentSource::knet(), // ->all(), ->card(), ->applePay(), ->mada(), ->fawry(), ->stcPay(), ->benefit(), ->benefitPay(), ->qpay(), ->omannet(), ->tabbyInstallment(), ->token($id), ->savedCard($id)
    'redirect' => ['url' => 'https://example.com/redirect'],
]);

if (ChargeStatus::from($charge['status'])->isSuccessful()) {
    // ...
}
```

The full list of known ids lives in `TapCompany\LaravelSdk\Enums\PaymentSourceId`.

_Samples adapted from Tap’s public reference docs. Field names match the API; placeholder URLs/IDs are from Tap examples._
