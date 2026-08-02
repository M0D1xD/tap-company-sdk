# Merchants

## Sources
- [Create a Merchant](https://developers.tap.company/reference/create-a-merchant.md)
- [List All Merchants](https://developers.tap.company/reference/list-all-merchants.md)

## Request

```json
{
  "display_name": "Flexwares",
  "business_id": "bus_uITwK4822102a8ye23cB9g556",
  "business_entity_id": "ent_iDTwK4822102vRLG239U9h562",
  "brand_id": "brd_p0TwK322103Il27234M9h207",
  "branch_id": "brc_oETwK322103Om1s23Oa9R301",
  "wallet_id": "wal_vlTwK4822102vK0s23Up9M562",
  "charge_currenices": [
    "KWD"
  ],
  "bank_account": {
    "iban": "INBNK00045545555555555555"
  },
  "settlement_by": "Bank"
}
```

## Response

Tap’s Create / List Merchant reference pages currently ship empty JSON examples (`{}`) in OpenAPI. Use the request above against the live/sandbox API, or inspect the response in the Tap dashboard. Request field names above are from [create-a-merchant.md](https://developers.tap.company/reference/create-a-merchant.md).

## SDK usage

```php
use TapCompany\LaravelSdk\Facades\Tap;

$merchant = Tap::merchants()->create([
    'display_name' => 'Flexwares',
    'business_id' => 'bus_uITwK4822102a8ye23cB9g556',
    'business_entity_id' => 'ent_iDTwK4822102vRLG239U9h562',
    'brand_id' => 'brd_p0TwK322103Il27234M9h207',
    'branch_id' => 'brc_oETwK322103Om1s23Oa9R301',
    'wallet_id' => 'wal_vlTwK4822102vK0s23Up9M562',
    'charge_currenices' => ['KWD'],
    'bank_account' => [
        'iban' => 'INBNK00045545555555555555',
    ],
    'settlement_by' => 'Bank',
]);
```

_Samples adapted from Tap’s public reference docs. Field names match the API; placeholder URLs/IDs are from Tap examples._
