<?php

declare(strict_types=1);

namespace TapCompany\LaravelSdk\Resources;

use TapCompany\LaravelSdk\Data\TapObject;

class Businesses extends AbstractResource
{
    /**
     * @param  array<string, mixed>  $payload
     */
    public function create(array $payload): TapObject
    {
        return $this->post('business', $payload);
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    public function list(array $filters = []): TapObject
    {
        return $this->get('business', $filters);
    }

    public function retrieve(string $businessId): TapObject
    {
        return $this->get("business/{$businessId}");
    }
}
