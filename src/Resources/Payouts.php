<?php

declare(strict_types=1);

namespace TapCompany\LaravelSdk\Resources;

use TapCompany\LaravelSdk\Data\TapObject;

class Payouts extends AbstractResource
{
    public function retrieve(string $payoutId): TapObject
    {
        return $this->get("payouts/{$payoutId}");
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    public function list(array $filters = []): TapObject
    {
        return $this->post('payouts/list', $filters);
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    public function download(array $filters = []): string
    {
        return $this->client->download('payouts/download', $filters);
    }
}
