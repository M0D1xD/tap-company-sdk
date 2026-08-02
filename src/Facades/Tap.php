<?php

declare(strict_types=1);

namespace TapCompany\LaravelSdk\Facades;

use Illuminate\Support\Facades\Facade;
use TapCompany\LaravelSdk\Http\TapHttpClient;
use TapCompany\LaravelSdk\Resources\Authorizations;
use TapCompany\LaravelSdk\Resources\Businesses;
use TapCompany\LaravelSdk\Resources\Cards;
use TapCompany\LaravelSdk\Resources\Charges;
use TapCompany\LaravelSdk\Resources\Connect;
use TapCompany\LaravelSdk\Resources\Customers;
use TapCompany\LaravelSdk\Resources\Destinations;
use TapCompany\LaravelSdk\Resources\Disputes;
use TapCompany\LaravelSdk\Resources\Files;
use TapCompany\LaravelSdk\Resources\Intents;
use TapCompany\LaravelSdk\Resources\Invoices;
use TapCompany\LaravelSdk\Resources\Leads;
use TapCompany\LaravelSdk\Resources\Merchants;
use TapCompany\LaravelSdk\Resources\Payouts;
use TapCompany\LaravelSdk\Resources\Refunds;
use TapCompany\LaravelSdk\Resources\Tokens;
use TapCompany\LaravelSdk\Webhooks\SignatureValidator;

/**
 * @method static TapHttpClient client()
 * @method static SignatureValidator webhooks()
 * @method static Charges charges()
 * @method static Authorizations authorizations()
 * @method static Refunds refunds()
 * @method static Customers customers()
 * @method static Tokens tokens()
 * @method static Cards cards()
 * @method static Invoices invoices()
 * @method static Intents intents()
 * @method static Payouts payouts()
 * @method static Leads leads()
 * @method static Businesses businesses()
 * @method static Merchants merchants()
 * @method static Destinations destinations()
 * @method static Files files()
 * @method static Disputes disputes()
 * @method static Connect connect()
 *
 * @see \TapCompany\LaravelSdk\Tap
 */
class Tap extends Facade
{
    /**
     * @param  array<string, mixed>  $config
     */
    public static function configure(array $config): void
    {
        \TapCompany\LaravelSdk\Tap::configure($config);
    }

    protected static function getFacadeAccessor(): string
    {
        return \TapCompany\LaravelSdk\Tap::class;
    }
}
