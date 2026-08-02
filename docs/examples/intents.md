# Intents

## Sources
- [Intents overview](https://developers.tap.company/reference/intents.md)
- [Create an Intent](https://developers.tap.company/reference/create-an-intent.md)
- [Retrieve an Intent](https://developers.tap.company/reference/retrieve-an-intent.md)
- [Cancel an Intent](https://developers.tap.company/reference/cancel-an-intent.md)

## Request

```json
{
  "scope": "CHARGE",
  "present_payments": "true",
  "idempotent": "xxx",
  "merchant": {
    "id": "merchant_gfxxx6EP4b733",
    "terminal": {
      "id": "terminal_Zxxx62Q11H982",
      "terminal_device": {
        "id": "terminal_device_scTwK252xxx6rt11t883",
        "serial_number": "564xxx888"
      }
    }
  },
  "order": {
    "amount": "1",
    "currency": "KWD",
    "reference": "xxx",
    "items": {
      "count": "1"
    },
    "discount": {
      "type": "F",
      "value": "1"
    },
    "shipping": {
      "amount": "1",
      "address": {
        "type": "home",
        "line1": "line1",
        "line2": "line2",
        "line3": "line3",
        "line4": "line4",
        "city": "salmyia",
        "state": "kuwait",
        "country": "kw",
        "zip_code": "30003",
        "postal_code": "30003"
      },
      "provider": {
        "id": "prov_FFSFAGGAHAAJAJ"
      }
    },
    "metadata": {
      "cycle": "1"
    }
  },
  "customer": {
    "name_on_card": {
      "content": "test card",
      "editable": "true"
    },
    "contact": {
      "email": "test@tap.company",
      "phone": {
        "country_code": "965",
        "number": "650000000"
      }
    },
    "address": {
      "type": "home",
      "line1": "line1",
      "line2": "line2",
      "line3": "line3",
      "line4": "line4",
      "city": "salmyia",
      "state": "kuwait",
      "country": "kw",
      "zip_code": "30003"
    }
  },
  "post": {
    "url": "https://post-url"
  }
}
```

## Response

```json
{
  "id": "intent_9jLs49261338SeXM160s199",
  "created": 1771249129009,
  "updated": 1771249133609,
  "object": "intent",
  "live_mode": "true",
  "api_version": "v2",
  "feature_version": "v1",
  "status": "INITIATED",
  "scope": "CHARGE",
  "initiator": "POS",
  "present_payments": true,
  "customer_initiated": true,
  "response": {
    "code": "100",
    "message": "Initiated"
  },
  "merchant": {
    "id": "merchant_RTTwK1726831gdOO11rM1B552",
    "name": "terminal testing account terminal testing account KW",
    "legacy_id": "68020459",
    "terminal": {
      "id": "terminal_V6TwK29267453QZY16WW1j510",
      "terminal_device": {
        "id": "terminal_device_1qTwK2726745xZ6L16PY15383",
        "serial_number": "1853590675"
      }
    },
    "payment_provider": {
      "technology": {
        "id": "technology_EqJf46231515pLzg7GN84113"
      },
      "institution": {
        "id": "payment_facilitator_tlG13723844En8426VA9143"
      }
    }
  },
  "authenticate": {
    "id": "",
    "required": true
  },
  "charge": {
    "save_card": false,
    "threeDSecure": true
  },
  "destinations": {},
  "topup": {},
  "transaction": {
    "card_holder_login": {
      "type": "",
      "timestamp": ""
    },
    "reference": "",
    "payment_agreement": {
      "id": "",
      "contract": {
        "id": ""
      }
    }
  },
  "order": {
    "id": "ord_zxQu48261338mtGy16CC14887",
    "amount": 10,
    "currency": "KWD",
    "items": {
      "count": 1,
      "list": [
        {
          "id": "itm_8kSQ48261338LJ1x163K1Z888",
          "quantity": 1,
          "pickup": false,
          "product": {
            "id": "prd_lJE5482613388P9f16Zg1p888",
            "name": [],
            "description": [],
            "category": "PHYSICAL_GOODS"
          }
        }
      ]
    },
    "metadata": {
      "cycle": "1"
    }
  },
  "customer": {
    "id": "cus_LV07H4920261638Ru2h1602652",
    "name": [
      {
        "title": "MS",
        "first": "Test",
        "middle": "",
        "last": "Test",
        "lang": "EN"
      }
    ],
    "name_on_card": {
      "content": "Test Test",
      "editable": true
    },
    "contact": {
      "email": "test@tap.company",
      "phone": {
        "country_code": "965",
        "number": "00000000"
      }
    },
    "address": {
      "type": "home",
      "line1": "sdfghjk",
      "line2": "oiuytr",
      "line3": "line3",
      "line4": "line4",
      "city": "salmyia",
      "state": "kuwait",
      "country": "kw",
      "zip_code": "30003"
    }
  },
  "post": {
    "url": "https://webhook.site/2b4d63d3-5da1-45c7-b038-1c065130e4a1"
  },
  "metadata": {
    "terminal_transaction_id": "terminal_transaction_LV4i1c492613387PFd16Eu1t59"
  },
  "track": {
    "id": "trk_2tFp49261338ePX7169Y1c9",
    "status": "INTENT_INITIATED",
    "activities": [
      {
        "id": "act_NIfi49261338bIVR16VT1U9",
        "action": "INTENT_INITIATED",
        "comment": "Intent created",
        "created": "2026-02-16T13:38:49.009947344"
      }
    ]
  }
}
```

## Response (cancel)

Tap’s [Cancel an Intent](https://developers.tap.company/reference/cancel-an-intent.md) page currently documents error examples only (e.g. intent not `INITIATED`). A successful cancel returns the intent object with an updated cancelled status — call `Tap::intents()->cancel($id)` and inspect the `TapObject` payload.

## SDK usage

```php
use TapCompany\LaravelSdk\Facades\Tap;

$intent = Tap::intents()->create([
    // POS intent fields from Create an Intent
]);

Tap::intents()->cancel($intent->id());
```

_Samples adapted from Tap’s public reference docs. Field names match the API; placeholder URLs/IDs are from Tap examples._
