<?php

declare(strict_types=1);

namespace TapCompany\LaravelSdk\Resources;

use TapCompany\LaravelSdk\Data\TapObject;

class Intents extends AbstractResource
{
    /**
     * @param  array<string, mixed>  $payload
     */
    public function create(array $payload): TapObject
    {
        return $this->post('intent', $this->withMerchant($payload));
    }

    public function retrieve(string $intentId): TapObject
    {
        return $this->get("intent/{$intentId}");
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    public function list(array $filters = []): TapObject
    {
        return $this->post('intent/list', $filters);
    }

    public function cancel(string $intentId): TapObject
    {
        return $this->post("intent/{$intentId}/cancel");
    }
}
