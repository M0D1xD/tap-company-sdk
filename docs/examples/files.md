# Files

## Sources
- [Files overview](https://developers.tap.company/reference/files.md)
- [Create a File](https://developers.tap.company/reference/create-a-file.md)

## Request (multipart/form-data)

From Create a File OpenAPI `multipart/form-data` schema defaults.

```json
{
  "purpose": "identity_document",
  "file": "(binary upload — RFC 2388 multipart file part)",
  "title": "Civil ID",
  "expires_at": "1913743462",
  "file_link_create": true
}
```

Possible `purpose` values (per Tap): `business_icon`, `business_logo`, `customer_signature`, `dispute_evidence`, `finance_report_run`, `identity_document`, `pci_document`, `sigma_scheduled_query`, `tax_document_user_upload`.

## Response

From the Files overview sample.

```json
{
  "id": "file_641212279714869248",
  "object": "file",
  "live_mode": false,
  "api_version": "1.0",
  "feature_version": "1.0",
  "created": 1572947320,
  "filename": "8801760e0a28ae2105e4ada503e30b8c.jpg",
  "purpose": "identity_document",
  "size": 346827,
  "title": "test",
  "type": "jpg",
  "url": "/files/file_641212279714869248",
  "links": [
    {
      "id": "link_641212281036075008",
      "object": "file_link",
      "live_mode": true,
      "api_version": "1.0",
      "feature_version": "1.0",
      "created": 1572947320,
      "expired": false,
      "expires_at": 1234567,
      "metadata": {
        "key1": "value1",
        "key2": "value2"
      },
      "url": "/links/fl_test_641212281036075009"
    }
  ]
}
```

## SDK usage

```php
use TapCompany\LaravelSdk\Facades\Tap;

$file = Tap::files()->create(
    [
        'purpose' => 'identity_document',
        'title' => 'Civil ID',
        'expires_at' => '1913743462',
        'file_link_create' => true,
    ],
    [
        'path' => storage_path('app/kyc.jpg'),
        'name' => 'file',
        'filename' => 'kyc.jpg',
    ],
);
```

_Samples adapted from Tap’s public reference docs. Field names match the API; placeholder URLs/IDs are from Tap examples._
