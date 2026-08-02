# Tap API object examples

Full request/response object samples for every resource exposed by `m0d1xd/tap-company-sdk`, adapted from Tap’s public Markdown reference pages (append `.md` to each docs URL).

## How these were sourced

1. Fetched `https://developers.tap.company/reference/<slug>.md` (and `docs/webhook.md`).
2. Preferred overview pages with embedded JSON samples (e.g. Charges).
3. Otherwise extracted OpenAPI `default` request bodies and `examples` response values from the Create/Retrieve pages.
4. Normalized invalid literals such as `false/true` comments to valid JSON.
5. Where Tap’s page ships an empty example (`{}`) or only errors, that is called out in the file.

Official index: [developers.tap.company/llms.txt](https://developers.tap.company/llms.txt).

## Index

| Resource | File | Primary Tap docs |
|----------|------|------------------|
| Charges | [charges.md](charges.md) | [charges](https://developers.tap.company/reference/charges.md), [create](https://developers.tap.company/reference/create-a-charge.md) |
| Authorizations | [authorizations.md](authorizations.md) | [authorize](https://developers.tap.company/reference/authorize.md), [create](https://developers.tap.company/reference/create-an-authorize.md) |
| Refunds | [refunds.md](refunds.md) | [refunds](https://developers.tap.company/reference/refunds.md), [create](https://developers.tap.company/reference/create-a-refund.md) |
| Customers | [customers.md](customers.md) | [create](https://developers.tap.company/reference/create-a-customer.md) |
| Tokens | [tokens.md](tokens.md) | [card](https://developers.tap.company/reference/create-a-token.md), [saved](https://developers.tap.company/reference/create-a-token-from-saved-card.md), [Apple Pay](https://developers.tap.company/reference/create-applepay-tap-token.md), … |
| Cards | [cards.md](cards.md) | [cards](https://developers.tap.company/reference/cards-2.md), [verify](https://developers.tap.company/reference/verify-a-card.md) |
| Invoices | [invoices.md](invoices.md) | [create](https://developers.tap.company/reference/create-an-invoice.md) |
| Intents | [intents.md](intents.md) | [intents](https://developers.tap.company/reference/intents.md), [create](https://developers.tap.company/reference/create-an-intent.md) |
| Payouts | [payouts.md](payouts.md) | [retrieve](https://developers.tap.company/reference/retrieve-a-payout.md), [list](https://developers.tap.company/reference/list-payouts.md) |
| Leads | [leads.md](leads.md) | [lead v3](https://developers.tap.company/reference/create-a-lead-v3.md), [retailer](https://developers.tap.company/reference/create-a-lead-for-retailer.md) |
| Businesses | [businesses.md](businesses.md) | [business](https://developers.tap.company/reference/business.md) |
| Merchants | [merchants.md](merchants.md) | [create](https://developers.tap.company/reference/create-a-merchant.md) |
| Destinations | [destinations.md](destinations.md) | [destinations](https://developers.tap.company/reference/destinations.md) |
| Files | [files.md](files.md) | [files](https://developers.tap.company/reference/files.md), [create](https://developers.tap.company/reference/create-a-file.md) |
| Disputes | [disputes.md](disputes.md) | [download](https://developers.tap.company/reference/download-disputes.md) |
| Connect | [connect.md](connect.md) | [connect URL](https://developers.tap.company/reference/create-a-connect-url.md) |
| Webhooks | [webhooks.md](webhooks.md) | [webhook guide](https://developers.tap.company/docs/webhook.md) |

Each file includes **Sources**, full **Request** / **Response** JSON where available, notable **variants**, and a short **SDK usage** snippet with `Tap::…()`.
