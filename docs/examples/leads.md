# Leads

## Sources
- [Lead overview](https://developers.tap.company/reference/lead.md)
- [Create a Lead (v3)](https://developers.tap.company/reference/create-a-lead-v3.md)
- [Create lead for retailer](https://developers.tap.company/reference/create-a-lead-for-retailer.md)
- [Convert lead to retailer](https://developers.tap.company/reference/convert-lead-to-a-retailer.md)

## Request (merchant lead v3)

```json
{
  "country": "AE",
  "brand": {
    "segment": {
      "type": {
        "code": "non_profit"
      },
      "team": {
        "code": "small"
      }
    }
  },
  "entity": {
    "carries_a_license": true,
    "license": {
      "number": "1234567",
      "country": "AE",
      "type": "LLC"
    }
  },
  "wallet": {
    "currency": "AED",
    "linked_financial_account": {
      "bank": {
        "account": {
          "name": "John",
          "number": "123456789",
          "swift": "AEXXXXXX",
          "iban": "AE123456789012345"
        }
      }
    }
  },
  "post": {
    "url": "https://www.example.com/postURL"
  }
}
```

## Response (merchant lead v3)

```json
{
  "id": "led_xxxx",
  "brand": {
    "name": {
      "en": "merchantNameEn",
      "ar": "merchantNameAr"
    },
    "logo": "file_656848322076980748",
    "operations": {
      "sales": {
        "period": "monthly",
        "range": {
          "from": "10000",
          "to": "80000"
        },
        "currency": "AED"
      }
    },
    "channel_services": [
      {
        "channel": "website",
        "address": "https://www.website.company/"
      },
      {
        "channel": "website",
        "address": "https://www.website.company/"
      }
    ]
  },
  "entity": {
    "country": "AE",
    "is_licensed": true,
    "license": {
      "number": "1010000000",
      "country": "AE",
      "type": "commercial_registration"
    },
    "tax": {
      "number": "123456789",
      "issuing_date": "2022-01-01",
      "expiry_date": "2025-01-01"
    }
  },
  "wallet": {
    "bank": {
      "name": "ADIB",
      "account": {
        "name": "ABC",
        "number": "77777777777",
        "swift": "77777777777",
        "iban": "SA000000000000000009"
      }
    }
  },
  "user": {
    "name": {
      "lang": "en",
      "title": "Mr",
      "first": "John",
      "last": "Doe"
    },
    "nationality": "AE",
    "identification": {
      "number": "00000000",
      "type": "national_id",
      "issuer": "AE"
    },
    "birth": {
      "country": "AE",
      "city": "Dubai",
      "date": "1990-01-01"
    },
    "primary": true
  },
  "post": {
    "url": "https://merchant.company/post_url"
  },
  "metadata": {
    "param1": "value"
  },
  "reference_lead_id": "xxx"
}
```

## Request (retailer lead)

```json
{
  "segment": {
    "type": "BUSINESS",
    "sub_segment": {
      "type": "RETAILER"
    }
  },
  "country": "SA",
  "brand": {
    "logo": "file_123456789"
  },
  "entity": {
    "license": "123456789"
  },
  "wallet": {
    "linked_financial_account": {
      "bank": {
        "account": {
          "name": "Beneficiary Name",
          "iban": "SA123000000456789"
        }
      }
    }
  },
  "marketplace": {
    "id": "12345678"
  },
  "post": {
    "url": "https://webhook.site/123456789"
  }
}
```

## Response (retailer lead)

```json
{
  "id": "led_abvdefg123456789",
  "status": "registered",
  "created": 1765384940984,
  "object": "lead",
  "live_mode": true,
  "api_version": "v3",
  "feature_version": "v1",
  "country": "SA",
  "pending_action": "tap",
  "last_contact": 0,
  "segment": {
    "type": "BUSINESS",
    "sub_segment": {
      "type": "RETAILER"
    }
  },
  "brand": {
    "id": "brd_123456789",
    "name": [
      {
        "lang": "en",
        "text": "retailer-tap-testing-04"
      }
    ],
    "logo": "file_3W7X2825740RuWv8C911t942",
    "operations": {
      "sales": {
        "id": "sub_range_V74129221433Nqj328AT9C430",
        "name": {
          "ar": "1,000,001 إلى 10,000,000",
          "en": "1,000,001 to 10,000,000"
        },
        "currency": "SAR",
        "range": {
          "from": 100001,
          "to": 10000000
        },
        "sub": [
          {
            "id": "sub_range_V74129221433Nqj328AT9C430",
            "name": {
              "ar": "5,000,001 الي 10,000,000",
              "en": "5,000,001 to 10,000,0000"
            },
            "range": {
              "from": 5000001,
              "to": 10000000
            }
          }
        ]
      },
      "customer_base": {
        "id": "customer_range_jdabj83748bajdaj28",
        "name": {
          "ar": "501 إلى 1,000",
          "en": "501 to 1,000"
        },
        "locations": [
          {
            "id": "customer_base_0ApZ28221441WzZz26Gh9H805",
            "code": "local",
            "name": {
              "ar": "محلي",
              "en": "Local"
            }
          }
        ]
      }
    },
    "terms": [
      {
        "term": "general",
        "agree": true,
        "agreed_at": 1765384971644
      },
      {
        "term": "chargeback",
        "agree": true,
        "agreed_at": 1765384971644
      },
      {
        "term": "refund",
        "agree": true,
        "agreed_at": 1765384971644
      }
    ],
    "channel_services": [
      {
        "id": "sales_channel_L3me8221219f4rQ28dX9v337",
        "code": "website",
        "name": {
          "ar": "متجر الكتروني",
          "en": "Website"
        },
        "address": "https://www.website.net/",
        "logo": "https://dash.b-cdn.net/icons/menu/website_v2.svg",
        "channel": "website"
      }
    ]
  },
  "users": [
    {
      "name": [
        {
          "first": "User",
          "middle": "name",
          "last": "last",
          "lang": "en"
        }
      ],
      "contact": {
        "email": [
          {
            "address": "retailer@website.com",
            "primary": true
          }
        ],
        "phone": [
          {
            "country_code": "966",
            "number": "555555555",
            "primary": true
          }
        ]
      },
      "identification": {
        "number": "1234567894",
        "country": "SA",
        "type": "national_id",
        "nationality": "SA"
      },
      "birth": {
        "country": "SA",
        "date": "1998-09-22"
      },
      "primary": true
    }
  ],
  "entity": {
    "id": "ent_123456789",
    "legal_name": [
      {
        "lang": "en",
        "text": "Entity Legal Name"
      }
    ],
    "carries_a_license": true,
    "license": {
      "number": "700900009",
      "country": "SA",
      "type": "LLC",
      "documents": [
        {
          "name": "commercial_registration",
          "issuing_country": "SA",
          "images": [
            "file_3W7X2825740RuWv8C911t942"
          ]
        }
      ]
    }
  },
  "wallet": {
    "name": [
      {
        "lang": "en",
        "text": "Wallet Display name text"
      }
    ],
    "linked_financial_account": {
      "bank": {
        "account": {
          "name": "Beneficiary Name",
          "iban": "SA123000000456789"
        },
        "documents": [
          {
            "name": "bank_statement",
            "issuing_country": "SA",
            "images": [
              "file_3W7X2825740RuWv8C911t942"
            ]
          }
        ]
      }
    }
  },
  "merchant": {
    "marketplace": false
  },
  "post": {
    "merchant_url": "https://webhook.site/123456789"
  },
  "marketplace": {
    "id": "merchant_123456789",
    "brand": {
      "id": "brd_123456789",
      "status": "Active",
      "created": 1696854780000,
      "name": [
        {
          "lang": "ar",
          "text": "حساب تجريبي"
        },
        {
          "lang": "en",
          "text": "Testing accounth"
        }
      ],
      "logo": "file_1201837960837480448"
    },
    "legacy_id": "26123567"
  }
}
```

## Response (convert to retailer)

```json
{
  "auth": {},
  "lead": {
    "id": "led_8CUaS3326814jFu813f90i549",
    "status": "registered",
    "contact": {
      "email": "retailer01@tap.com",
      "phone": {
        "country_code": "966",
        "number": "555555555"
      }
    }
  },
  "retailer": {
    "id": "123456",
    "status": {
      "payout": false
    }
  }
}
```

## SDK usage

```php
use TapCompany\LaravelSdk\Facades\Tap;

$lead = Tap::leads()->create([/* merchant lead v3 payload */]);
$retailerLead = Tap::leads()->createRetailer([/* retailer lead payload */]);
$account = Tap::leads()->convertToRetailer($retailerLead->id());
```

_Samples adapted from Tap’s public reference docs. Field names match the API; placeholder URLs/IDs are from Tap examples._
