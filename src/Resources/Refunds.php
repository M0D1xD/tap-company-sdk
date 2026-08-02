<?php

declare(strict_types=1);

namespace TapCompany\LaravelSdk\Resources;

use TapCompany\LaravelSdk\Data\TapObject;
use TapCompany\LaravelSdk\Http\TapHttpClient;

class Refunds extends AbstractResource
{
    /**
     * @param  array<string, mixed>  $payload
     */
    public function create(array $payload, ?string $idempotencyKey = null): TapObject
    {
        return $this->post(
            'refunds',
            $payload,
            TapHttpClient::idempotencyHeaders($idempotencyKey),
        );
    }

    public function retrieve(string $refundId): TapObject
    {
        return $this->get("refunds/{$refundId}");
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    public function list(array $filters = []): TapObject
    {
        return $this->post('refunds/list', $filters);
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    public function download(array $filters = []): string
    {
        return $this->client->download('refunds/download', $filters);
    }
}
