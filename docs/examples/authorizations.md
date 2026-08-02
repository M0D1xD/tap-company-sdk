# Authorizations

## Sources
- [Authorize overview](https://developers.tap.company/reference/authorize.md)
- [Create an Authorize](https://developers.tap.company/reference/create-an-authorize.md)

## Request

```json
{
  "amount": 1,
  "currency": "KWD",
  "customer_initiated": true,
  "threeDSecure": true,
  "save_card": false,
  "statement_descriptor": "",
  "receipt": {
    "email": true,
    "sms": true
  },
  "metadata": {
    "udf1": "test_data_1",
    "udf2": "test_data_2",
    "udf3": "test_data_3"
  },
  "reference": {
    "transaction": "txn_0001",
    "order": "ord_0001"
  },
  "customer": {
    "first_name": "Test",
    "middle_name": "Test",
    "last_name": "Test",
    "email": "test@test.com",
    "phone": {
      "country_code": "965",
      "number": "50000000"
    }
  },
  "merchant": {
    "id": "1234"
  },
  "source": {
    "id": "src_card"
  },
  "authorize_debit": false,
  "auto": {
    "type": "VOID",
    "time": 100
  },
  "post": {
    "url": "http://your_website.com/posturl"
  },
  "redirect": {
    "url": "http://your_website.com/redirecturl"
  }
}
```

## Response

```json
{
  "id": "auth_TS065220221533k5FK1505634",
  "object": "authorize",
  "live_mode": false,
  "api_version": "V2",
  "method": "CREATE",
  "status": "INITIATED",
  "amount": 1,
  "currency": "KWD",
  "threeDSecure": true,
  "save_card": false,
  "merchant_id": "",
  "product": "",
  "statement_descriptor": "sample",
  "transaction": {
    "timezone": "UTC+03:00",
    "created": "1652628832666",
    "url": "https://sandbox.payments.tap.company/test_gosell/v2/payment/response.aspx?tap_auth=qU%2fYHoOr4blXyIfDLNpJrPnvbvtHyf4yqjGjChH5aTk%3d&sess=6mXrG%2fSKpa0%3d&token=qU%2fYHoOr4blXyIfDLNpJrPnvbvtHyf4yee5pNiSxqVQ28X2kDyKgPg%3d%3d",
    "expiry": {
      "period": 30,
      "type": "MINUTE"
    },
    "asynchronous": false,
    "amount": 1,
    "currency": "KWD"
  },
  "reference": {
    "transaction": "txn_0001",
    "order": "ord_0001"
  },
  "response": {
    "code": "100",
    "message": "Initiated"
  },
  "receipt": {
    "email": true,
    "sms": true
  },
  "customer": {
    "first_name": "Test",
    "last_name": "Test",
    "email": "test@test.com",
    "phone": {
      "country_code": "965",
      "number": "50000000"
    }
  },
  "source": {
    "object": "source",
    "id": "src_card"
  },
  "redirect": {
    "status": "PENDING",
    "url": "http://your_website.com/redirecturl"
  },
  "post": {
    "status": "PENDING",
    "url": "http://your_website.com/posturl"
  },
  "auto": {
    "status": "PENDING",
    "type": "VOID",
    "time": 100
  },
  "metadata": {
    "udf1": "test_data_1",
    "udf2": "test_data_2",
    "udf3": "test_data_3"
  }
}
```

## SDK usage

```php
use TapCompany\LaravelSdk\Facades\Tap;

$auth = Tap::authorizations()->create([
    'amount' => 1,
    'currency' => 'KWD',
    'customer_initiated' => true,
    'threeDSecure' => true,
    'save_card' => false,
    'customer' => [
        'first_name' => 'test',
        'last_name' => 'test',
        'email' => 'test@test.com',
        'phone' => ['country_code' => '965', 'number' => '51234567'],
    ],
    'source' => ['id' => 'src_card'],
    'redirect' => ['url' => 'https://example.com/redirect'],
]);

// Capture later
$charge = Tap::authorizations()->capture($auth->id(), [
    'amount' => 1,
    'currency' => 'KWD',
    'customer' => ['id' => data_get($auth->toArray(), 'customer.id')],
]);
```

_Samples adapted from Tap’s public reference docs. Field names match the API; placeholder URLs/IDs are from Tap examples._
