<?php

declare(strict_types=1);

namespace TapCompany\LaravelSdk;

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

class Tap
{
    protected ?Charges $charges = null;

    protected ?Authorizations $authorizations = null;

    protected ?Refunds $refunds = null;

    protected ?Customers $customers = null;

    protected ?Tokens $tokens = null;

    protected ?Cards $cards = null;

    protected ?Invoices $invoices = null;

    protected ?Intents $intents = null;

    protected ?Payouts $payouts = null;

    protected ?Leads $leads = null;

    protected ?Businesses $businesses = null;

    protected ?Merchants $merchants = null;

    protected ?Destinations $destinations = null;

    protected ?Files $files = null;

    protected ?Disputes $disputes = null;

    protected ?Connect $connect = null;

    public function __construct(
        protected TapHttpClient $client,
        protected SignatureValidator $signatureValidator,
    ) {
    }

    /**
     * Merge values into the Tap config at runtime (secret key, webhook path, etc.).
     *
     * Prefer calling this from a service provider before the Tap client is resolved
     * (and before the application has finished booting, for webhook route options).
     *
     * @param  array<string, mixed>  $config
     */
    public static function configure(array $config): void
    {
        config([
            'tap' => array_replace_recursive(config('tap', []), $config),
        ]);
    }

    public function client(): TapHttpClient
    {
        return $this->client;
    }

    public function webhooks(): SignatureValidator
    {
        return $this->signatureValidator;
    }

    public function charges(): Charges
    {
        return $this->charges ??= new Charges($this->client);
    }

    public function authorizations(): Authorizations
    {
        return $this->authorizations ??= new Authorizations($this->client);
    }

    public function refunds(): Refunds
    {
        return $this->refunds ??= new Refunds($this->client);
    }

    public function customers(): Customers
    {
        return $this->customers ??= new Customers($this->client);
    }

    public function tokens(): Tokens
    {
        return $this->tokens ??= new Tokens($this->client);
    }

    public function cards(): Cards
    {
        return $this->cards ??= new Cards($this->client);
    }

    public function invoices(): Invoices
    {
        return $this->invoices ??= new Invoices($this->client);
    }

    public function intents(): Intents
    {
        return $this->intents ??= new Intents($this->client);
    }

    public function payouts(): Payouts
    {
        return $this->payouts ??= new Payouts($this->client);
    }

    public function leads(): Leads
    {
        return $this->leads ??= new Leads($this->client);
    }

    public function businesses(): Businesses
    {
        return $this->businesses ??= new Businesses($this->client);
    }

    public function merchants(): Merchants
    {
        return $this->merchants ??= new Merchants($this->client);
    }

    public function destinations(): Destinations
    {
        return $this->destinations ??= new Destinations($this->client);
    }

    public function files(): Files
    {
        return $this->files ??= new Files($this->client);
    }

    public function disputes(): Disputes
    {
        return $this->disputes ??= new Disputes($this->client);
    }

    public function connect(): Connect
    {
        return $this->connect ??= new Connect($this->client);
    }
}
