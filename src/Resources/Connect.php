<?php

declare(strict_types=1);

namespace TapCompany\LaravelSdk\Resources;

use TapCompany\LaravelSdk\Data\TapObject;

class Connect extends AbstractResource
{
    /**
     * Create a Tap Connect onboarding URL from a lead.
     *
     * @param  array<string, mixed>  $payload
     */
    public function createUrl(string $leadId, array $payload = []): TapObject
    {
        $payload['lead_id'] = $payload['lead_id'] ?? $leadId;

        return $this->post('connect', $payload);
    }
}
