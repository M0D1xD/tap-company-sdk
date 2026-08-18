<?php

declare(strict_types=1);

namespace TapCompany\LaravelSdk\Support;

use Illuminate\Http\Client\Request;
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

    /** @var (callable(array<string, mixed>): void)|null */
    protected $dumper;

    /** @var (callable(): mixed)|null */
    protected $terminator;

    /**
     * @param  (callable(array<string, mixed>): void)|null  $dumper
     * @param  (callable(): mixed)|null  $terminator
     */
    public function __construct(?callable $dumper = null, ?callable $terminator = null)
    {
        $this->dumper = $dumper;
        $this->terminator = $terminator;
    }

    /**
     * Build a redacted snapshot of the request that is about to be sent.
     *
     * @return array{method: string, url: string, headers: array<string, string>, body: array<string, mixed>}
     */
    public function snapshot(Request $request): array
    {
        return [
            'method' => strtoupper($request->method()),
            'url' => $request->url(),
            'headers' => $this->redactHeaders($request->headers()),
            'body' => $this->snapshotBody($request),
        ];
    }

    /**
     * @param  array<string, mixed>  $snapshot
     */
    public function inspect(array $snapshot, bool $halt = false): void
    {
        ($this->dumper ?? 'dump')($snapshot);

        if ($halt) {
            ($this->terminator ?? static function (): never {
                exit(1);
            })();
        }
    }

    /**
     * @param  array<string, mixed>|null  $requestPayload
     * @param  array<string, mixed>|string|null  $responsePayload
     * @param  array<string, string>|null  $headers
     */
    public function outgoing(
        string $method,
        string $url,
        ?array $requestPayload,
        int $status,
        array|string|null $responsePayload,
        ?array $headers = null,
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

        if ($headers !== null) {
            $context['headers'] = $this->redactHeaders($headers);
        }

        if ($this->logPayloads()) {
            $context['request'] = $this->redact($this->normalize($requestPayload ?? []));
            $context['response'] = is_string($responsePayload)
                ? $responsePayload
                : $this->redact($this->normalize($responsePayload ?? []));
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
            $context['payload'] = $this->redact($this->normalize($payload));
        }

        $this->write('Tap incoming webhook', $context);
    }

    /**
     * JSON-normalize Arrayable / JsonSerializable values to match the wire payload.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function normalize(array $data): array
    {
        $encoded = json_encode($data);

        if ($encoded === false) {
            return $data;
        }

        $decoded = json_decode($encoded, true);

        return is_array($decoded) ? $decoded : $data;
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
     * @return array<string, mixed>
     */
    protected function snapshotBody(Request $request): array
    {
        if ($request->isJson()) {
            $raw = $request->body();

            if ($raw !== '') {
                $decoded = json_decode($raw, true);

                if (is_array($decoded)) {
                    /** @var array<string, mixed> $decoded */
                    return $this->redact($this->normalize($decoded));
                }
            }
        }

        $data = $request->data();

        if (! is_array($data)) {
            $data = [];
        }

        /** @var array<string, mixed> $data */
        if ($request->isMultipart()) {
            $data = $this->omitMultipartFiles($data);
        }

        return $this->redact($this->normalize($data));
    }

    /**
     * @param  array<array-key, mixed>  $headers
     * @return array<string, string>
     */
    protected function redactHeaders(array $headers): array
    {
        $redacted = [];

        foreach ($headers as $name => $values) {
            $header = (string) $name;
            $value = is_array($values) ? implode(', ', $values) : (string) $values;

            if ($this->shouldRedactKey($header) || $this->looksLikeBearerToken($value)) {
                $redacted[$header] = '[redacted]';

                continue;
            }

            $redacted[$header] = $value;
        }

        return $redacted;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function omitMultipartFiles(array $data): array
    {
        $omitted = [];

        foreach ($data as $key => $value) {
            if (is_array($value) && (array_key_exists('contents', $value) || array_key_exists('filename', $value))) {
                $omitted[$key] = [
                    'name' => $value['name'] ?? $key,
                    'filename' => $value['filename'] ?? null,
                    'contents' => '[file omitted]',
                ];

                continue;
            }

            if (is_array($value)) {
                /** @var array<string, mixed> $value */
                $omitted[$key] = $this->omitMultipartFiles($value);

                continue;
            }

            $omitted[$key] = $value;
        }

        return $omitted;
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
