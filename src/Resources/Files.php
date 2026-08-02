<?php

declare(strict_types=1);

namespace TapCompany\LaravelSdk\Resources;

use TapCompany\LaravelSdk\Data\TapObject;

class Files extends AbstractResource
{
    /**
     * Upload a file (KYC / onboarding documents).
     *
     * @param  array<string, mixed>  $payload
     * @param  array{path?: string, contents?: string|resource, name?: string, filename?: string}|null  $file
     */
    public function create(array $payload = [], ?array $file = null): TapObject
    {
        return $this->client->postMultipart('files', $payload, $file);
    }

    public function retrieve(string $fileId): TapObject
    {
        return $this->get("files/{$fileId}");
    }
}
