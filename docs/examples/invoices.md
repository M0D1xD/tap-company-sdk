# Invoices

## Sources
- [Create an Invoice](https://developers.tap.company/reference/create-an-invoice.md)
- [Update an Invoice](https://developers.tap.company/reference/update-an-invoice.md)
- [List all Invoices](https://developers.tap.company/reference/list-all-invoices.md)

## Request

```json
{
  "draft": false,
  "due": 1672235072000,
  "expiry": 1672235072000,
  "description": "test invoice",
  "mode": "INVOICE",
  "note": "test note",
  "notifications": {
    "channels": [
      "SMS",
      "EMAIL"
    ],
    "dispatch": true
  },
  "currencies": [
    "KWD"
  ],
  "metadata": {
    "udf1": "1",
    "udf2": "2",
    "udf3": "3"
  },
  "charge": {
    "receipt": {
      "email": true,
      "sms": true
    }
  },
  "customer": {
    "first_name": "test",
    "last_name": "test",
    "email": "test@test.com",
    "phone": {
      "country_code": "965",
      "number": "51234567"
    }
  },
  "statement_descriptor": "test",
  "order": {
    "amount": "10",
    "currency": "KWD"
  },
  "post": {
    "url": "http://your_website.com/post_url"
  },
  "redirect": {
    "url": "http://your_website.com/redirect_url"
  },
  "reference": {
    "invoice": "INV_00001",
    "order": "ORD_00001"
  },
  "retry_for_captured": true
}
```

## Response

```json
{
  "object": "invoice",
  "live_mode": false,
  "api_version": "V1.2",
  "id": "inv_9eaR1311012NSf3527897",
  "method": "CREATE",
  "status": "CREATED",
  "amount": 12,
  "currency": "KWD",
  "created": 1690184054897,
  "updated": 1690184064008,
  "url": "https://invoices.sandbox.tap.company/invoice/inv_9eaR1311012NSf3527897",
  "draft": false,
  "due": 1690268010000,
  "expiry": 1690268010000,
  "mode": "INVOICE",
  "description": "test invoice",
  "description_id": "desc_p5AR1311012lu2P527527",
  "invoice_number": "120230724073421624",
  "frequency": "SINGLE_INVOICE",
  "lang_code": "EN",
  "metadata": {
    "udf1": "1",
    "udf2": "2",
    "udf3": "3"
  },
  "notifications": {
    "dispatch": true,
    "channels": [
      "SMS",
      "EMAIL"
    ]
  },
  "order": {
    "object": "order",
    "id": "ord_6H3l1311012pQ7p527357",
    "live_mode": false,
    "api_version": "V1.2",
    "currency": "KWD",
    "amount": 12,
    "status": "CREATED",
    "items": [
      {
        "id": "itm_Et0Y1311012mh2a527047",
        "product_id": "prd_ylcq1311012p4z3527357",
        "name": "test",
        "description": "test",
        "image": "",
        "currency": "KWD",
        "amount": 10,
        "quantity": 1,
        "discount": {
          "type": "P",
          "value": 0
        },
        "merchant_id": ""
      }
    ],
    "tax": [
      {
        "id": "tax_3EXI1311012Q3K4527143",
        "name": "VAT",
        "description": "test",
        "rate": {
          "type": "F",
          "value": 1
        }
      }
    ],
    "shipping": {
      "id": "shp_uUtp1311012yoZh527240",
      "currency": "KWD",
      "amount": 1,
      "provider": "ARAMEX",
      "service": "test",
      "description": "test"
    },
    "itemAmount": 10,
    "created": 1690184059357,
    "merchant_id": "599424"
  },
  "reference": {
    "invoice": "INV_00001",
    "order": "ORD_00001"
  },
  "customer": {
    "id": "cus_TS03A1820231035i5FK2407026",
    "first_name": "test",
    "middle_name": "test",
    "last_name": "test",
    "email": "test@test.com",
    "phone": {
      "number": "51234567",
      "country_code": "965"
    }
  },
  "post": {
    "url": "http://your_website.com/post_url"
  },
  "redirect": {
    "url": "http://your_website.com/redirect_url"
  },
  "charge": {
    "receipt": {
      "email": true,
      "sms": true
    },
    "statement_descriptor": "test"
  },
  "track": {
    "id": "tck_x0AD1311012HAz0527527",
    "object": "track",
    "status": "DELIVERED",
    "updated": 1690184064008,
    "activity": [
      {
        "id": "act_ykJJ13110123Xmg527527",
        "object": "activity",
        "type": "CREATED",
        "created": 1690184061527
      },
      {
        "id": "act_EMVS1311012oyov527008",
        "object": "activity",
        "type": "DELIVERED",
        "created": 1690184064008
      }
    ]
  },
  "payment_methods": [
    ""
  ],
  "currencies": [
    "KWD"
  ],
  "savecard": false,
  "note": "test note",
  "merchant_id": ""
}
```

## SDK usage

```php
use TapCompany\LaravelSdk\Facades\Tap;

$invoice = Tap::invoices()->create([/* invoice payload */]);
$invoice = Tap::invoices()->retrieve($invoice->id());
```

_Samples adapted from Tap’s public reference docs. Field names match the API; placeholder URLs/IDs are from Tap examples._
