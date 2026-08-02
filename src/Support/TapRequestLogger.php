<?php

declare(strict_types=1);

namespace TapCompany\LaravelSdk\Support;

use Illuminate\Support\Facades\Log;
use Throwable;

class TapRequestLogger
{
    /** @var list<string> */
    protected const REDACT_KEYS = [
        'authorization',
        'secret_key',
        'secret',
        'password',
        'cvc',
        'cvv',
        'card_number',
    ];

    /**
     * @param  array<string, mixed>|null  $requestPayload
     * @param  array<string, mixed>|string|null  $responsePayload
     */
    public function outgoing(
        string $method,
        string $url,
        ?array $requestPayload,
        int $status,
        array|string|null $responsePayload,
    ): void {
        if (! $this->enabled()) {
            return;
        }

        $context = [
            'direction' => 'outgoing',
            'method' => strtoupper($method),
            'url' => $url,
            'status' => $status,
        ];

        if ($this->logPayloads()) {
            $context['request'] = $this->redact($requestPayload ?? []);
            $context['response'] = is_string($responsePayload)
                ? $responsePayload
                : $this->redact($responsePayload ?? []);
        }

        $this->write('Tap outgoing request', $context);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function incoming(string $path, array $payload, int $status): void
    {
        if (! $this->enabled()) {
            return;
        }

        $context = [
            'direction' => 'incoming',
            'method' => 'POST',
            'path' => $path,
            'status' => $status,
        ];

        if ($this->logPayloads()) {
            $context['payload'] = $this->redact($payload);
        }

        $this->write('Tap incoming webhook', $context);
    }

    protected function enabled(): bool
    {
        return (bool) config('tap.logging.enabled', false);
    }

    protected function logPayloads(): bool
    {
        return (bool) config('tap.logging.log_payloads', true);
    }

    /**
     * @param  array<string, mixed>  $context
     */
    protected function write(string $message, array $context): void
    {
        try {
            $channel = (string) config('tap.logging.channel', 'tap');
            $level = (string) config('tap.logging.level', 'debug');

            Log::channel($channel)->log($level, $message, $context);
        } catch (Throwable) {
            // Logging must never break API or webhook handling.
        }
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function redact(array $data): array
    {
        $redacted = [];

        foreach ($data as $key => $value) {
            if (is_string($key) && $this->shouldRedactKey($key)) {
                $redacted[$key] = '[redacted]';

                continue;
            }

            if (is_array($value)) {
                /** @var array<string, mixed> $value */
                $redacted[$key] = $this->redact($value);

                continue;
            }

            if (is_string($value) && $this->looksLikeBearerToken($value)) {
                $redacted[$key] = '[redacted]';

                continue;
            }

            $redacted[$key] = $value;
        }

        return $redacted;
    }

    protected function shouldRedactKey(string $key): bool
    {
        $normalized = strtolower($key);

        return in_array($normalized, self::REDACT_KEYS, true);
    }

    protected function looksLikeBearerToken(string $value): bool
    {
        return str_starts_with(strtolower($value), 'bearer ');
    }
}
