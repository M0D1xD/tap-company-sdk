# Webhooks

## Sources
- [Webhook guide](https://developers.tap.company/docs/webhook.md)
- [Payout webhook API](https://developers.tap.company/reference/webhook-api.md)

## Charge webhook payload (body)

```json
{
  "id": "chg_TS05A4120230736x9K22710693",
  "object": "charge",
  "live_mode": false,
  "customer_initiated": true,
  "api_version": "V2",
  "method": "POST",
  "status": "CAPTURED",
  "amount": 1.0,
  "currency": "SAR",
  "threeDSecure": true,
  "card_threeDSecure": false,
  "save_card": true,
  "merchant_id": "",
  "product": "",
  "description": "",
  "metadata": {
    "udf1": "test_data_1",
    "udf2": "test_data_2",
    "udf3": "test_data_3"
  },
  "transaction": {
    "timezone": "UTC+03:00",
    "created": "1698392202943",
    "expiry": {
      "period": 30,
      "type": "MINUTE"
    },
    "asynchronous": false,
    "amount": 1.0,
    "currency": "SAR"
  },
  "reference": {
    "track": "tck_TS04A4320230736To522710661",
    "payment": "4327230736106619650",
    "gateway": "mada_pg70983e7a-a686-40ba-83e2-c5e9f4074fe5",
    "acquirer": "230004002581",
    "transaction": "txn_0001",
    "order": "ord_0001"
  },
  "response": {
    "code": "000",
    "message": "Captured"
  },
  "security": {
    "threeDSecure": {
      "status": "Y"
    }
  },
  "gateway": {
    "response": {
      "code": "000",
      "message": "Approved"
    }
  },
  "card": {
    "id": "card_IIGi4523416sFHe27jJ9E589",
    "object": "card",
    "first_six": "446404",
    "first_eight": "44640400",
    "scheme": "MADA",
    "brand": "VISA",
    "last_four": "0007"
  },
  "receipt": {
    "id": "204327230736104914",
    "email": true,
    "sms": true
  },
  "customer": {
    "id": "cus_TS07A5420232136o2K52709053",
    "first_name": "Majdi",
    "middle_name": "Abdullah",
    "last_name": "Al Khowaiter",
    "email": "m.ghgjhgj@tap.company",
    "phone": {
      "country_code": "966",
      "number": "51234567"
    }
  },
  "merchant": {
    "country": "SA",
    "currency": "SAR",
    "id": "25145693"
  },
  "source": {
    "object": "token",
    "type": "CARD_NOT_PRESENT",
    "payment_type": "DEBIT",
    "payment_method": "MADA",
    "channel": "INTERNET",
    "id": "tok_nLKq4223436fVYL27Nj9P855"
  },
  "redirect": {
    "status": "PENDING",
    "url": "http://your_website.com/redirecturl"
  },
  "post": {
    "attempt": 1,
    "status": "PENDING",
    "url": "https://webhook.site/25c5e885-216b-4d5f-bcbf-8e5d0d20b76f"
  },
  "activities": [
    {
      "id": "activity_TS03A4420230736Rb512710536",
      "object": "activity",
      "created": 1698392202943,
      "status": "INITIATED",
      "currency": "SAR",
      "amount": 1.0,
      "remarks": "charge - created"
    },
    {
      "id": "activity_TS07A1320230737j1H42710453",
      "object": "activity",
      "created": 1698392233453,
      "status": "CAPTURED",
      "currency": "SAR",
      "amount": 1.0,
      "remarks": "charge - captured"
    }
  ],
  "auto_reversed": false,
  "payment_agreement": {
    "id": "payment_agreement_23Ah4423436I5R027SS9c330",
    "amount_variability": "VARIABLE",
    "type": "UNSCHEDULED",
    "total_payments_count": 1,
    "contract": {
      "id": "card_IIGi4523416sFHe27jJ9E589",
      "customer_id": "cus_TS07A5420232136o2K52709053",
      "type": "SAVED_CARD"
    },
    "metadata": {
      "txn_type": "CHARGE",
      "txn_id": "chg_TS05A4120230736x9K22710693",
      "terminal_id": "ter_g6P51020221643Rj631205942"
    }
  }
}
```

## Authorize webhook payload (body)

```json
{
  "id": "auth_TS04A1720230745Rt2a2710607",
  "object": "authorize",
  "customer_initiated": true,
  "authorize_debit": false,
  "live_mode": false,
  "api_version": "V2",
  "status": "AUTHORIZED",
  "amount": 100.0,
  "currency": "SAR",
  "threeDSecure": true,
  "save_card": true,
  "merchant_id": "",
  "product": "",
  "transaction": {
    "authorization_id": "125468",
    "timezone": "UTC+03:00",
    "created": "1698392719404",
    "expiry": {
      "period": 30,
      "type": "MINUTE"
    },
    "asynchronous": false,
    "amount": 100.0,
    "currency": "SAR"
  },
  "reference": {
    "track": "tck_TS02A2020230745q4MN2710966",
    "payment": "2027230745109668360",
    "gateway": "123456789",
    "acquirer": "330004125468",
    "transaction": "txn_0001",
    "order": "ord_0001"
  },
  "response": {
    "code": "001",
    "message": "Authorized"
  },
  "security": {
    "threeDSecure": {
      "id": "3ds_TS05A1920230745Tb452710404",
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
    "id": "card_2V741723717UcVF17Yh9O354",
    "object": "card",
    "first_six": "512345",
    "first_eight": "51234500",
    "scheme": "MASTERCARD",
    "brand": "MASTERCARD",
    "last_four": "0008"
  },
  "receipt": {
    "id": "202127230745103892",
    "email": true,
    "sms": true
  },
  "customer": {
    "id": "cus_TS02A4520230208p3P21710602",
    "first_name": "Majdi",
    "middle_name": "Alal",
    "last_name": "Alal",
    "email": "m.ghgjhgj@tap.company",
    "phone": {
      "country_code": "966",
      "number": "51234567"
    }
  },
  "source": {
    "object": "token",
    "type": "CARD_NOT_PRESENT",
    "payment_type": "CREDIT",
    "payment_method": "MASTERCARD",
    "channel": "INTERNET",
    "id": "tok_udpI1923445Jo1b27mi9I282"
  },
  "redirect": {
    "status": "PENDING",
    "url": "http://your_website.com/redirecturl"
  },
  "post": {
    "status": "PENDING",
    "url": "https://webhook.site/25c5e885-216b-4d5f-bcbf-8e5d0d20b76f"
  },
  "auto": {
    "status": "SCHEDULED",
    "type": "VOID",
    "time": 1
  },
  "merchant": {
    "id": "25145693"
  },
  "description": "",
  "metadata": {
    "udf1": "test_data_1",
    "udf2": "test_data_2",
    "udf3": "test_data_3"
  },
  "payment_agreement": {
    "id": "payment_agreement_u6Xt2123445zy0z27yY9m684",
    "type": "UNSCHEDULED",
    "total_payments_count": 1,
    "contract": {
      "id": "card_2V741723717UcVF17Yh9O354",
      "customer_id": "cus_TS02A4520230208p3P21710602",
      "type": "SAVED_CARD"
    },
    "metadata": {
      "txn_type": "AUTHORIZE",
      "txn_id": "auth_TS04A1720230745Rt2a2710607",
      "terminal_id": "ter_m1RM0420211322p4PH1002445"
    }
  }
}
```

## Hashstring formula (charge / authorize / refund)

Concatenate:
x_id{id}x_amount{amount}x_currency{currency}x_gateway_reference{gateway}x_payment_reference{payment}x_status{status}x_created{created}

Then: hash_hmac('sha256', $string, $secretKey)

Amount must be rounded to currency decimals (KWD/BHD/OMR/JOD = 3; most others = 2).

## SDK usage

```php
use TapCompany\LaravelSdk\Facades\Tap;
use TapCompany\LaravelSdk\Events\ChargeUpdated;

// Package route (TAP_WEBHOOK_ENABLED=true): POST /tap/webhook

Event::listen(ChargeUpdated::class, function (ChargeUpdated $event): void {
    $charge = $event->payload;
});

// Manual verification
Tap::webhooks()->validateOrFail($request->all(), $request->header('hashstring'));
```

_Samples adapted from Tap’s public reference docs. Field names match the API; placeholder URLs/IDs are from Tap examples._
