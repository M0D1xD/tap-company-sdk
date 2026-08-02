<?php

declare(strict_types=1);

namespace TapCompany\LaravelSdk\Exceptions;

use Illuminate\Http\Client\Response;

class ApiRequestException extends TapException
{
    /**
     * @param  array<string, mixed>  $responseBody
     */
    public function __construct(
        string $message,
        public readonly int $statusCode,
        public readonly array $responseBody = [],
        public readonly ?Response $response = null,
    ) {
        parent::__construct($message, $statusCode);
    }

    /**
     * @param  array<string, mixed>  $body
     */
    public static function fromResponse(Response $response, array $body = []): self
    {
        $message = $body['errors'][0]['description']
            ?? $body['message']
            ?? $body['response']['message']
            ?? sprintf('Tap API request failed with status %d', $response->status());

        return new self(
            message: is_string($message) ? $message : 'Tap API request failed',
            statusCode: $response->status(),
            responseBody: $body,
            response: $response,
        );
    }
}
