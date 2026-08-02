<?php

declare(strict_types=1);

namespace TapCompany\LaravelSdk\Resources;

use TapCompany\LaravelSdk\Data\TapObject;
use TapCompany\LaravelSdk\Http\TapHttpClient;

class Authorizations extends AbstractResource
{
    /**
     * @param  array<string, mixed>  $payload
     */
    public function create(array $payload, ?string $idempotencyKey = null): TapObject
    {
        return $this->post(
            'authorize',
            $this->withMerchant($payload),
            TapHttpClient::idempotencyHeaders($idempotencyKey),
        );
    }

    public function retrieve(string $authorizeId): TapObject
    {
        return $this->get("authorize/{$authorizeId}");
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function update(string $authorizeId, array $payload): TapObject
    {
        return $this->put("authorize/{$authorizeId}", $payload);
    }

    /**
     * Capture an authorization by creating a charge with source.id = auth_*.
     *
     * @param  array<string, mixed>  $payload
     */
    public function capture(string $authorizeId, array $payload = [], ?string $idempotencyKey = null): TapObject
    {
        $payload['source'] = array_merge($payload['source'] ?? [], ['id' => $authorizeId]);

        return $this->post(
            'charges',
            $this->withMerchant($payload),
            TapHttpClient::idempotencyHeaders($idempotencyKey),
        );
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    public function download(array $filters = []): string
    {
        return $this->client->download('authorize/download', $filters);
    }
}
