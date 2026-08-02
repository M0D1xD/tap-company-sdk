<?php

declare(strict_types=1);

namespace TapCompany\LaravelSdk\Resources;

use TapCompany\LaravelSdk\Data\TapObject;

class Tokens extends AbstractResource
{
    /**
     * Create a token from raw card details (server-side / PCI flows only).
     *
     * @param  array<string, mixed>  $payload
     */
    public function create(array $payload): TapObject
    {
        return $this->post('tokens', $payload);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function createEncrypted(array $payload): TapObject
    {
        return $this->post('tokens', $payload);
    }

    /**
     * Create a token from a saved card (requires card_id + customer_id).
     *
     * @param  array<string, mixed>  $payload
     */
    public function createFromSavedCard(array $payload): TapObject
    {
        return $this->post('tokens', $payload);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function createApplePay(array $payload): TapObject
    {
        return $this->post('tokens', $payload);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function createSamsungPay(array $payload): TapObject
    {
        return $this->post('tokens', $payload);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function createNetworkToken(array $payload): TapObject
    {
        return $this->post('tokens', $payload);
    }

    public function retrieve(string $tokenId): TapObject
    {
        return $this->get("tokens/{$tokenId}");
    }
}
