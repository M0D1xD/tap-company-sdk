# Disputes

## Sources
- [Download Disputes](https://developers.tap.company/reference/download-disputes.md)

## Request (download filters)

```json
{
  "period": {
    "date": {
      "from": "1736507775000",
      "to": "1739186175000"
    }
  },
  "merchants": [
    "599424"
  ]
}
```

## Response notes

The download endpoint returns CSV content (binary/text), not a JSON object. Persist the returned string from `Tap::disputes()->download($filters)`.

## SDK usage

```php
use TapCompany\LaravelSdk\Facades\Tap;

$csv = Tap::disputes()->download([/* period / filters from docs */]);
file_put_contents(storage_path('app/disputes.csv'), $csv);
```

_Samples adapted from Tap’s public reference docs. Field names match the API; placeholder URLs/IDs are from Tap examples._
