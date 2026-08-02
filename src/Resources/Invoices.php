<?php

declare(strict_types=1);

namespace TapCompany\LaravelSdk\Resources;

use TapCompany\LaravelSdk\Data\TapObject;

class Invoices extends AbstractResource
{
    /**
     * @param  array<string, mixed>  $payload
     */
    public function create(array $payload): TapObject
    {
        return $this->post('invoices', $this->withMerchant($payload));
    }

    public function retrieve(string $invoiceId): TapObject
    {
        return $this->get("invoices/{$invoiceId}");
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function update(string $invoiceId, array $payload): TapObject
    {
        return $this->put("invoices/{$invoiceId}", $payload);
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    public function list(array $filters = []): TapObject
    {
        return $this->post('invoices/list', $filters);
    }
}
