<?php

declare(strict_types=1);

namespace TapCompany\LaravelSdk\Resources;

use TapCompany\LaravelSdk\Data\TapObject;

class Cards extends AbstractResource
{
    public function retrieve(string $customerId, string $cardId): TapObject
    {
        return $this->get("card/{$customerId}/{$cardId}");
    }

    /**
     * @param  array<string, mixed>  $query
     */
    public function list(string $customerId, array $query = []): TapObject
    {
        return $this->get("card/{$customerId}", $query);
    }

    public function delete(string $customerId, string $cardId): TapObject
    {
        return $this->client->delete("card/{$customerId}/{$cardId}");
    }

    /**
     * Verify a card (and optionally save it).
     *
     * @param  array<string, mixed>  $payload
     */
    public function verify(array $payload): TapObject
    {
        return $this->post('card/verify', $this->withMerchant($payload));
    }
}
