# Destinations

## Sources
- [Destinations overview](https://developers.tap.company/reference/destinations.md)
- [Retrieve a Destination](https://developers.tap.company/reference/retrieve-a-destination.md)
- [List All Destinations](https://developers.tap.company/reference/list-all-destinations.md)

## Object / sample

```json
{
  "display_name": "destplay",
  "business_id": "bus_Wntp1311012lnAh527153",
  "business_entity_id": "bsa_MxYZ1311012Viln527900",
  "brand_id": "brd_O4ZZ1311012Adr8527056",
  "branch_id": "brc_3Ud11311012OgEi527915",
  "bank_account": {
    "iban": "KWNBOKXXXXXXXXXXXXXXXXXXX"
  },
  "settlement_by": "Acquirer"
}
```

## Response (retrieve)

```json
{
  "id": "7691116100",
  "status": "Active",
  "created": 1573480740377,
  "object": "merchant",
  "live_mode": false,
  "api_version": "v2",
  "feature_version": "v2",
  "display_name": "mecplay",
  "business_id": "bus_jICJ50019921aGRG11Ja103291",
  "business_entity_id": "ent_QTFo56019921OSHN11Bv10y128",
  "brand_id": "brd_Uf1y59019921WrJI11h7107174",
  "branch_id": "brc_cemO2019922120F11jP10B63",
  "wallets": {
    "id": "wal_St480191659nfRg11aV108158",
    "status": "Active",
    "created": 1573480740158,
    "base_currency": "currency_YH3M1311012RFUc7214",
    "country": "country_as_QDVW13110125v1z7214",
    "settlement_by": "Acquirer",
    "primary_wallet": false
  },
  "bank_account": {
    "id": "bka_kKSB59191658lXvp11bY10h930",
    "status": "Active",
    "created": 1573480739930,
    "iban": "KWNBOKXXXXXXXXXXXXXXXXXXX"
  },
  "settlement_by": "Acquirer"
}
```

## Response (list)

```json
{}
```

## SDK usage

```php
use TapCompany\LaravelSdk\Facades\Tap;

$destination = Tap::destinations()->retrieve('dest_xxx');
$destinations = Tap::destinations()->list();
```

_Samples adapted from Tap’s public reference docs. Field names match the API; placeholder URLs/IDs are from Tap examples._
