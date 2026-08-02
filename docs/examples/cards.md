# Cards

## Sources
- [Cards overview](https://developers.tap.company/reference/cards-2.md)
- [Retrieve a Card](https://developers.tap.company/reference/retrieve-a-card.md)
- [List All Cards](https://developers.tap.company/reference/list-all-cards.md)
- [Verify a Card](https://developers.tap.company/reference/verify-a-card.md)
- [Delete a Card](https://developers.tap.company/reference/delete-a-card.md)

## Card object

```json
{
  "id": "card_C9vyl1311012RofB527622",
  "object": "card",
  "address": {
    "country": "Kuwait",
    "city": "Kuwait city",
    "avenue": "Gulf",
    "street": "Salim",
    "line1": "Salmiya, 21"
  },
  "customer": "cus_w4P24120191640b9N91106702",
  "funding": "CREDIT",
  "fingerprint": "Q%2FcqTEPF%2FZuM7IaWN%2F7QR8kjZsJ1zzAdrmAhTXaBTOk%3D",
  "brand": "VISA",
  "scheme": "VISA",
  "exp_month": 12,
  "exp_year": 25,
  "last_four": "2393",
  "first_six": "479045",
  "name": "test user",
  "issuer": {
    "bank": "KUWAIT FINANCE HOUSE",
    "country": "KW",
    "id": ""
  }
}
```

## Request (verify)

```json
{
  "currency": "KWD",
  "threeDSecure": true,
  "save_card": false,
  "metadata": {
    "udf1": "test1",
    "udf2": "test2"
  },
  "customer": {
    "first_name": "test",
    "middle_name": "test",
    "last_name": "test",
    "email": "test@test.com",
    "phone": {
      "country_code": "965",
      "number": "50000000"
    }
  },
  "redirect": {
    "url": "http://your_website.com/redirect_url"
  },
  "post": {
    "url": "http://your_website.com/post_url"
  }
}
```

## Response (verify)

```json
{
  "id": "vry_TS07A3420231554m1Y21506344",
  "object": "verify_card",
  "live_mode": false,
  "api_version": "V2",
  "status": "INITIATED",
  "currency": "KWD",
  "threeDSecure": true,
  "save_card": false,
  "metadata": {
    "udf1": "test1",
    "udf2": "test2"
  },
  "transaction": {
    "timezone": "UTC+03:00",
    "created": "1686844474360",
    "url": "https://sandbox.payments.tap.company/test_gosell/v2/payment/tap_process.aspx?auth=qU%2fYHoOr4bkq2tRwqeLL6fGJFGzQYnycswDRdh3z%2bWE%3d",
    "asynchronous": false,
    "amount": 0
  },
  "customer": {
    "first_name": "test",
    "last_name": "test",
    "email": "test@test.com",
    "phone": {
      "country_code": "965",
      "number": "50000000"
    }
  },
  "source": {
    "object": "token",
    "id": "tok_xxxxxxxxx"
  },
  "redirect": {
    "status": "PENDING",
    "url": "http://your_website.com/redirect_url"
  },
  "card": {
    "object": "card",
    "first_six": "xxxxxx",
    "last_four": "xxxx"
  },
  "response": {
    "code": "100",
    "message": "Initiated"
  },
  "risk": false,
  "issuer": false,
  "promo": false,
  "loyalty": false,
  "card_issuer": {
    "id": "bnk_TS04A3720231554a5JB1506453",
    "name": "",
    "country": "GB"
  }
}
```

## Response (list)

```json
{
  "object": "list",
  "has_more": false,
  "data": [
    {
      "id": "card_M4rZNlPKq8Ddk0QCgJoOARu2",
      "object": "card",
      "address_city": null,
      "address_country": null,
      "address_line1": null,
      "address_line2": null,
      "address_zip": null,
      "customer": "cus_c2HT20182216a9KB2615204",
      "funding": "CREDIT",
      "fingerprint": "PfkYgD6LcJrvwROek+FB0gVJw",
      "brand": "VISA",
      "exp_month": 10,
      "exp_year": 2020,
      "last4": "4242",
      "bin": "424242",
      "name": "John"
    }
  ]
}
```

## Response (delete)

```json
{
  "deleted": true,
  "id": "card_yQgS4922138M2ru9E05R787",
  "delete": true
}
```

## SDK usage

```php
use TapCompany\LaravelSdk\Facades\Tap;

$card = Tap::cards()->retrieve('cus_xxx', 'card_xxx');
$cards = Tap::cards()->list('cus_xxx');
$verification = Tap::cards()->verify([
    'currency' => 'KWD',
    'threeDSecure' => true,
    'save_card' => false,
    'customer' => [
        'first_name' => 'test',
        'last_name' => 'test',
        'email' => 'test@test.com',
        'phone' => ['country_code' => '965', 'number' => '50000000'],
    ],
    'source' => ['id' => 'tok_xxx'],
    'redirect' => ['url' => 'https://example.com/redirect'],
    'post' => ['url' => 'https://example.com/post'],
]);

Tap::cards()->delete('cus_xxx', 'card_xxx');
```

_Samples adapted from Tap’s public reference docs. Field names match the API; placeholder URLs/IDs are from Tap examples._
