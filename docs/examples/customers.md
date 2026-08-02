# Customers

## Sources
- [Create a Customer](https://developers.tap.company/reference/create-a-customer.md)
- [Retrieve a Customer](https://developers.tap.company/reference/retrieve-a-customer.md)
- [List Customers](https://developers.tap.company/reference/customer-list.md)

## Request (create)

From Create a Customer OpenAPI defaults.

```json
{
  "first_name": "First",
  "middle_name": "Middle",
  "last_name": "Last",
  "email": "customer@test.com",
  "phone": {
    "country_code": "965",
    "number": "512345678"
  }
}
```

## Response

Tap’s create/retrieve customer pages do not currently embed a standalone success example. The customer object returned on charges (same fields + `id`) from [charges.md](https://developers.tap.company/reference/charges.md):

```json
{
  "id": "cus_TS06A5220231551Rl7y1408020",
  "first_name": "Waleed",
  "last_name": "Asghar",
  "email": "w.asghar@tap.company",
  "phone": {
    "country_code": "971",
    "number": "586275033"
  }
}
```

## SDK usage

```php
use TapCompany\LaravelSdk\Facades\Tap;

$customer = Tap::customers()->create([
    'first_name' => 'Waleed',
    'last_name' => 'Asghar',
    'email' => 'w.asghar@tap.company',
    'phone' => [
        'country_code' => '971',
        'number' => '586275033',
    ],
]);

$customer = Tap::customers()->retrieve($customer->id());
```

_Samples adapted from Tap’s public reference docs. Field names match the API; placeholder URLs/IDs are from Tap examples._
