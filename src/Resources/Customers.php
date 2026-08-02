<?php

declare(strict_types=1);

namespace TapCompany\LaravelSdk\Resources;

use TapCompany\LaravelSdk\Data\TapObject;

class Customers extends AbstractResource
{
    /**
     * @param  array<string, mixed>  $payload
     */
    public function create(array $payload): TapObject
    {
        return $this->post('customers', $payload);
    }

    public function retrieve(string $customerId): TapObject
    {
        return $this->get("customers/{$customerId}");
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function update(string $customerId, array $payload): TapObject
    {
        return $this->put("customers/{$customerId}", $payload);
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    public function list(array $filters = []): TapObject
    {
        return $this->get('customers', $filters);
    }

    public function delete(string $customerId): TapObject
    {
        return $this->client->delete("customers/{$customerId}");
    }
}
