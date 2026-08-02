# Businesses

## Sources
- [Business overview](https://developers.tap.company/reference/business.md)
- [Create a Business](https://developers.tap.company/reference/create-a-business.md)
- [List All Businesses](https://developers.tap.company/reference/list-all-businesses.md)

## Request

```json
{
  "name": {
    "en": "Flexwares",
    "ar": "فلكس ويرزدفع"
  },
  "type": "corp",
  "entity": {
    "legal_name": {
      "en": "Flexwares",
      "ar": "فلكس ويرزدفع"
    },
    "is_licensed": "true",
    "license": {
      "type": "Commercial Registration",
      "number": "2134342SE"
    },
    "not_for_profit": false,
    "country": "KW",
    "tax_number": "1234567890",
    "documents": [
      {
        "type": "Commercial Registration",
        "number": "1234567890",
        "issuing_country": "KW",
        "issuing_date": "2019-07-09",
        "expiry_date": "2021-07-09",
        "files": [
          "file_984450183956131840"
        ]
      }
    ],
    "bank_account": {
      "iban": "INBNK00045545555555555555",
      "swift_code": "SWFT12345678909836435647",
      "account_number": "DFGHGFVB876215bsdjhkn"
    },
    "billing_address": {
      "recipient_name": "test",
      "address_1": "Address one",
      "address_2": "Address two",
      "po_box": "0000",
      "district": "Salmiya",
      "city": "Hawally",
      "state": "Kuwait",
      "zip_code": "30003",
      "country": "KW"
    }
  },
  "contact_person": {
    "name": {
      "title": "Mr",
      "first": "Test",
      "middle": "Test",
      "last": "Test"
    },
    "contact_info": {
      "primary": {
        "email": "test@test.com",
        "phone": {
          "country_code": "965",
          "number": "51234567"
        }
      }
    },
    "nationality": "KW",
    "date_of_birth": "2000-01-02",
    "is_authorized": true,
    "authorization": {
      "name": {
        "title": "Mr",
        "first": "Test",
        "middle": "Test",
        "last": "Test"
      },
      "type": "identity_document",
      "issuing_country": "KW",
      "issuing_date": "2012-03-03",
      "expiry_date": "2020-03-03",
      "files": [
        "file_984450183956131840"
      ]
    },
    "identification": [
      {
        "name": {
          "title": "Mr",
          "first": "Test",
          "middle": "Test",
          "last": "Test"
        },
        "type": "identity_document",
        "number": "123456789",
        "issuing_country": "KW",
        "issuing_date": "2012-02-02",
        "expiry_date": "2012-02-02",
        "files": [
          "file_984450183956131840"
        ]
      }
    ]
  },
  "brands": [
    {
      "name": {
        "en": "Flexwares",
        "ar": "فلكس ويرزدفع"
      },
      "sector": [
        "Others"
      ],
      "website": "https://www.flexwares.company/",
      "social": [
        "https://add.cc"
      ],
      "logo": "file_984450183956131840",
      "content": {
        "tag_line": {
          "en": "Walk free",
          "ar": "المشي الحرتروني",
          "zh": "自由走"
        },
        "about": {
          "en": "The Flexwares is a shoe store company selling awsome and long lasting shoes. Come and check out our products online.",
          "ar": "هذه هي شركة لبيع الأحذية تبيع أحذية رهيبة وطويلة الأمد. تعال وتحقق من منتجاتنا عبر الإنتر",
          "zh": "这是一家鞋店公司，销售长久耐用的鞋子。快来在线查看我们的产品。"
        }
      }
    }
  ],
  "post": {
    "url": "http://flexwares.company/post_url"
  },
  "metadata": {
    "mtd": "metadata"
  }
}
```

## Response

```json
{}
```

## SDK usage

```php
use TapCompany\LaravelSdk\Facades\Tap;

$business = Tap::businesses()->create([/* business payload */]);
$businesses = Tap::businesses()->list();
```

_Samples adapted from Tap’s public reference docs. Field names match the API; placeholder URLs/IDs are from Tap examples._
