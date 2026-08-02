<?php

declare(strict_types=1);

namespace TapCompany\LaravelSdk\Resources;

use TapCompany\LaravelSdk\Data\TapObject;
use TapCompany\LaravelSdk\Http\TapHttpClient;

class Charges extends AbstractResource
{
    /**
     * @param  array<string, mixed>  $payload
     */
    public function create(array $payload, ?string $idempotencyKey = null): TapObject
    {
        return $this->post(
            'charges',
            $this->withMerchant($payload),
            TapHttpClient::idempotencyHeaders($idempotencyKey),
        );
    }

    public function retrieve(string $chargeId): TapObject
    {
        return $this->get("charges/{$chargeId}");
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function update(string $chargeId, array $payload): TapObject
    {
        return $this->put("charges/{$chargeId}", $payload);
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    public function list(array $filters = []): TapObject
    {
        return $this->post('charges/list', $filters);
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    public function download(array $filters = []): string
    {
        return $this->client->download('charges/download', $filters);
    }
}
