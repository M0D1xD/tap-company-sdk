# Tokens

## Sources
- [Create a Token (Card)](https://developers.tap.company/reference/create-a-token.md)
- [Encrypted Card](https://developers.tap.company/reference/create-a-token-encrypted-card.md)
- [Saved Card](https://developers.tap.company/reference/create-a-token-from-saved-card.md)
- [Apple Pay](https://developers.tap.company/reference/create-applepay-tap-token.md)
- [Samsung Pay](https://developers.tap.company/reference/create-samsungpay-token.md)
- [Network Token](https://developers.tap.company/reference/create-a-token-network-token.md)

## Request (card)

```json
{
  "card": {
    "number": 4508750015741019,
    "exp_month": 1,
    "exp_year": 2039,
    "cvc": 100,
    "name": "test user",
    "address": {
      "country": "Kuwait",
      "line1": "Salmiya, 21",
      "city": "Kuwait city",
      "street": "Salim",
      "avenue": "Gulf"
    }
  },
  "client_ip": "192.168.1.20"
}
```

## Response (card)

```json
{
  "id": "tok_SpTV5823926VPgE27bU3O801",
  "created": 1682587618801,
  "object": "token",
  "live_mode": false,
  "type": "CARD",
  "used": false,
  "card": {
    "id": "card_u35O5823926okiJ27sk3D805",
    "object": "card",
    "address": {
      "country": "Kuwait",
      "city": "Kuwait city",
      "avenue": "Gulf",
      "street": "Salim",
      "line1": "Salmiya, 21"
    },
    "funding": "DEBIT",
    "fingerprint": "E1I78d8PV0UHivCrvV8dTkI%2FQJRHPow4XAU4FfmZxCQ%3D",
    "brand": "VISA",
    "scheme": "VISA",
    "name": "test user",
    "issuer": {
      "bank": "THE CO-OPERATIVE BANK PLC",
      "country": "GB",
      "id": "bnk_TS06A1120231227Kx242704090"
    },
    "exp_month": 1,
    "exp_year": 39,
    "last_four": "1019",
    "first_six": "450875"
  }
}
```

## Request (saved card)

```json
{
  "saved_card": {
    "card_id": "card_RtPG1623834SD1e278H2q99",
    "customer_id": "cus_TS01A3020231134Ks4x2703949"
  },
  "client_ip": "127.0.0.1"
}
```

## Response (saved card)

```json
{
  "id": "tok_TS77A22256459Cjg11eO11o953",
  "status": "ACTIVE",
  "created": 1765435522944,
  "object": "token",
  "live_mode": false,
  "type": "CARD",
  "purpose": "CHARGE",
  "used": false,
  "card": {
    "id": "card_TS76A1825644n8u911yv111459",
    "object": "card",
    "on_file": true,
    "customer": "cus_TS04A3820250644j2P41112349",
    "funding": "DEBIT",
    "fingerprint": "FGO1kscijell9pPAgEY5zhFoq%2Fqg%2BUWvlHk%2BKpccRks%3D",
    "brand": "VISA",
    "scheme": "VISA",
    "exp_month": 1,
    "exp_year": 39,
    "last_four": "1019",
    "first_six": "450875",
    "first_eight": "45087500",
    "name": "TEST CARD"
  },
  "payment": {
    "id": "card_TS76A1825644n8u911yv111459",
    "on_file": true,
    "card_data": {
      "exp_month": 1,
      "exp_year": 39,
      "last_four": "1019",
      "first_six": "450875",
      "first_eight": "45087500"
    },
    "fingerprint": "FGO1kscijell9pPAgEY5zhFoq%2Fqg%2BUWvlHk%2BKpccRks%3D",
    "scheme": "VISA"
  },
  "merchant": {
    "id": "1234567"
  },
  "client_ip": "127.0.0.1"
}
```

## Request (Apple Pay)

```json
{
  "type": "applepay",
  "token_data": {
    "data": "CM8i9PNK4yXtKO3xmOn6uyYOWmQ+iX9/Oc0EWHJZnPZ/IAEe2UYNCfely3dgq3veEygmQcl0s8lvMeCIZAbbBvbZWPKng9lfUwP2u3IUOFfFyI4beE9znpQ/e0nyQiVh8NFyZun8o0/YZfdFhaBy8bunveULZkWODZy3vg1LLTk0wSRfzbiFav/krgeMvztl8U85Fefl1VJVoJbW/jtShwDkusHizw/p/hkLiOFcCYSz7h9culZQMTWfqsxIfTuY3mOl+NhjAHPP+UFv4wefXrQL9MKO2cI6ttXOp5k6M6mFV/Qe0fbmJ6GnDWDMSiikW+3eL0yi0IApAKmmVgPS+uk42dyhrnSPhB6A7EJBmhEEb3ErL1I69Jq9REjDHp+VoZR0fAbDtpbjKKMo",
    "header": {
      "transactionId": "0c4352c073ad460044517596dbbf8fe503a837138c8c2de18fddb37ca3ec5295",
      "publicKeyHash": "LjAAyv6vb6jOEkjfG7L1a5OR2uCTHIkB61DaYdEWD+w=",
      "ephemeralPublicKey": "MFkwEwYHKoZIzj0CAQYIKoZIzj0DAQcDQgAELAfDie0Ie1TxCcrFt69BzcQ52+F+Fhm5mDw6pMR54AzoFMgdGPRbqoLtFpoSe0FI/m0cqRMOVM2W4Bz9jVZZHA=="
    },
    "signature": "bNEa18hOrgG/oFk/o0CtYR01vhm+34RbStas1T+tkFLpP0eG5A+9P7k9eYq8OL5q+V8xyRWrG8YcsV9JaHA32hNjS1UAPalnClSnrn2SXimafLGPv4OaImH/Ta9uuKPVdJAfa26UDtAYhlsXiBY5MAVytRUl+Cec5DkmihNwI7GJaR6mD1Hlz+7AFrHL31R+hPM4lVp3yJKsZYFzadUKpzZpjhub6iQG81WhN2LcBEpbf13ksOYHpUWpKaa9YHxpO2CnIGzEWwdxD8nFkWyEeCt/mFs1Lq504diIBaq57p+nNX+Iydy9LIsM4TvT4dj5Dv5gn5A3gbcEIuR3hcw+HWp",
    "version": "EC_v1"
  },
  "client_ip": "192.168.1.20"
}
```

## Response (Apple Pay)

```json
{
  "id": "tok_TS15A12261246GSKv5ui6m224",
  "status": "ACTIVE",
  "created": 1783255572218,
  "object": "token",
  "live_mode": false,
  "type": "APPLEPAY",
  "purpose": "CHARGE",
  "used": false,
  "card": {
    "id": "card_TS28A12261246PW4N5WP6F277",
    "object": "card",
    "on_file": false,
    "funding": "DEBIT",
    "fingerprint": "efgf%2F61xjF2rW0XTmxS%2BA1503anAec%2BD2fEKXyJw5OA%3D",
    "brand": "VISA",
    "scheme": "VISA",
    "category": "STANDARD",
    "exp_month": 9,
    "exp_year": 30,
    "last_four": "6180",
    "first_six": "467516",
    "first_eight": "46751634"
  },
  "payment": {
    "id": "card_TS28A12261246PW4N5WP6F277",
    "on_file": false,
    "card_data": {
      "exp_month": 9,
      "exp_year": 30,
      "last_four": "6180",
      "first_six": "467516",
      "first_eight": "46751634"
    },
    "fingerprint": "efgf%2F61xjF2rW0XTmxS%2BA1503anAec%2BD2fEKXyJw5OA%3D",
    "scheme": "VISA",
    "category": "STANDARD"
  },
  "merchant": {
    "id": "68046551"
  },
  "client_ip": "94.128.83.223",
  "pass_thru_wallet": {
    "saved_card": {
      "enabled": false
    }
  }
}
```

## Request (Samsung Pay)

```json
{
  "type": "samsungpay"
}
```

## Request (network token)

```json
{
  "token_data": {
    "scheme_token": {
      "number": "4818528890029444",
      "exp_month": "12",
      "exp_year": "26",
      "payment_data": {
        "cryptogram": "/wAAAAAAcPdr3/sAmgQugqgAAAA=",
        "eci": "07"
      },
      "name": "Tap"
    }
  },
  "type": "schemetoken"
}
```

## Response (network token)

```json
{
  "id": "tok_TS11A18251330JkRj18up7M451",
  "status": "ACTIVE",
  "created": 1755523818451,
  "object": "token",
  "live_mode": false,
  "type": "SCHEMETOKEN",
  "purpose": "CHARGE",
  "used": false,
  "card": {
    "id": "card_TS43A182513305aQu18547X558",
    "object": "card",
    "on_file": false,
    "funding": "DEBIT",
    "fingerprint": "RUEdVGdvrAT4TM7cV%2Bv1ZN8j33OrQLecMjf3RSXHVvI%3D",
    "brand": "VISA",
    "scheme": "VISA",
    "category": "BUSINESS",
    "exp_month": 12,
    "exp_year": 26,
    "last_four": "9444",
    "first_six": "481852",
    "first_eight": "48185288",
    "name": "Tap"
  },
  "payment": {
    "id": "card_TS43A182513305aQu18547X558",
    "on_file": false,
    "card_data": {
      "exp_month": 12,
      "exp_year": 26,
      "last_four": "9444",
      "first_six": "481852",
      "first_eight": "48185288"
    },
    "fingerprint": "RUEdVGdvrAT4TM7cV%2Bv1ZN8j33OrQLecMjf3RSXHVvI%3D",
    "scheme": "VISA",
    "category": "BUSINESS"
  },
  "merchant": {
    "id": "65752084"
  }
}
```

## SDK usage

```php
use TapCompany\LaravelSdk\Facades\Tap;

$token = Tap::tokens()->create([/* card payload from docs */]);
$saved = Tap::tokens()->createFromSavedCard([
    'saved_card' => [
        'card_id' => 'card_xxx',
        'customer_id' => 'cus_xxx',
    ],
]);
$apple = Tap::tokens()->createApplePay([/* Apple Pay token payload */]);
```

_Samples adapted from Tap’s public reference docs. Field names match the API; placeholder URLs/IDs are from Tap examples._
