<?php

declare(strict_types=1);

namespace TapCompany\LaravelSdk\Resources;

use TapCompany\LaravelSdk\Data\TapObject;

class Leads extends AbstractResource
{
    /**
     * Create a merchant lead (Tap Lead API v3).
     *
     * @param  array<string, mixed>  $payload
     */
    public function create(array $payload): TapObject
    {
        return $this->post('lead', $payload);
    }

    public function retrieve(string $leadId): TapObject
    {
        return $this->get("lead/{$leadId}");
    }

    /**
     * Create a retailer lead.
     *
     * @param  array<string, mixed>  $payload
     */
    public function createRetailer(array $payload): TapObject
    {
        return $this->post('lead/retailer', $payload);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function updateRetailer(string $leadId, array $payload): TapObject
    {
        return $this->put("lead/retailer/{$leadId}", $payload);
    }

    /**
     * Convert a retailer lead into a retailer account.
     *
     * @param  array<string, mixed>  $payload
     */
    public function convertToRetailer(string $leadId, array $payload = []): TapObject
    {
        return $this->post("lead/{$leadId}/convert", $payload);
    }
}
