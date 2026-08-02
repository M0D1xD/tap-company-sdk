<?php

declare(strict_types=1);

namespace TapCompany\LaravelSdk\Resources;

use TapCompany\LaravelSdk\Data\TapObject;
use TapCompany\LaravelSdk\Http\TapHttpClient;

abstract class AbstractResource
{
    public function __construct(protected TapHttpClient $client)
    {
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    protected function withMerchant(array $payload): array
    {
        $merchantId = config('tap.merchant_id');

        if ($merchantId && ! isset($payload['merchant'])) {
            $payload['merchant'] = ['id' => (string) $merchantId];
        }

        return $payload;
    }

    /**
     * @param  array<string, mixed>  $payload
     * @param  array<string, string>  $headers
     */
    protected function post(string $path, array $payload = [], array $headers = []): TapObject
    {
        return $this->client->post($path, $payload, $headers);
    }

    /**
     * @param  array<string, mixed>  $query
     * @param  array<string, string>  $headers
     */
    protected function get(string $path, array $query = [], array $headers = []): TapObject
    {
        return $this->client->get($path, $query, $headers);
    }

    /**
     * @param  array<string, mixed>  $payload
     * @param  array<string, string>  $headers
     */
    protected function put(string $path, array $payload = [], array $headers = []): TapObject
    {
        return $this->client->put($path, $payload, $headers);
    }
}
