<?php

declare(strict_types=1);

namespace TapCompany\LaravelSdk\Resources;

class Disputes extends AbstractResource
{
    /**
     * @param  array<string, mixed>  $filters
     */
    public function download(array $filters = []): string
    {
        return $this->client->download('disputes/download', $filters);
    }
}
