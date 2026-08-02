<?php

declare(strict_types=1);

namespace TapCompany\LaravelSdk\Resources;

use TapCompany\LaravelSdk\Data\TapObject;

class Destinations extends AbstractResource
{
    public function retrieve(string $destinationId): TapObject
    {
        return $this->get("destination/{$destinationId}");
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    public function list(array $filters = []): TapObject
    {
        return $this->get('destination', $filters);
    }
}
