<?php

declare(strict_types=1);

namespace TapCompany\LaravelSdk\Http;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use TapCompany\LaravelSdk\Data\TapObject;
use TapCompany\LaravelSdk\Exceptions\ApiRequestException;
use TapCompany\LaravelSdk\Exceptions\TapException;
use TapCompany\LaravelSdk\Support\TapRequestLogger;

class TapHttpClient
{
    public function __construct(
        protected string $secretKey,
        protected string $baseUrl,
        protected int $timeout = 15,
        protected int $connectTimeout = 5,
        protected int $retryTimes = 2,
        protected int $retrySleep = 200,
        protected ?TapRequestLogger $logger = null,
    ) {
        if ($this->secretKey === '') {
            throw new TapException(
                'Tap secret key is not configured. Set TAP_SECRET_KEY, config(\'tap.secret_key\'), or Tap::configure([\'secret_key\' => \'...\']).',
            );
        }

        $this->logger ??= new TapRequestLogger;
    }

    /**
     * @param  array<string, mixed>  $query
     * @param  array<string, string>  $headers
     */
    public function get(string $path, array $query = [], array $headers = []): TapObject
    {
        return $this->request('get', $path, query: $query, headers: $headers);
    }

    /**
     * @param  array<string, mixed>  $payload
     * @param  array<string, string>  $headers
     */
    public function post(string $path, array $payload = [], array $headers = []): TapObject
    {
        return $this->request('post', $path, payload: $payload, headers: $headers);
    }

    /**
     * @param  array<string, mixed>  $payload
     * @param  array<string, string>  $headers
     */
    public function put(string $path, array $payload = [], array $headers = []): TapObject
    {
        return $this->request('put', $path, payload: $payload, headers: $headers);
    }

    /**
     * @param  array<string, mixed>  $payload
     * @param  array<string, string>  $headers
     */
    public function delete(string $path, array $payload = [], array $headers = []): TapObject
    {
        return $this->request('delete', $path, payload: $payload, headers: $headers);
    }

    /**
     * @param  array<string, mixed>  $payload
     * @param  array<string, string>  $headers
     * @param  array<string, string>|null  $file  Keys: path, name, contents (optional), filename (optional)
     */
    public function postMultipart(string $path, array $payload = [], ?array $file = null, array $headers = []): TapObject
    {
        $request = $this->pendingRequest($headers);
        $normalizedPath = $this->normalizePath($path);

        if ($file !== null) {
            $request->attach(
                $file['name'] ?? 'file',
                $file['contents'] ?? file_get_contents($file['path']),
                $file['filename'] ?? basename($file['path'] ?? 'upload.bin'),
            );
        }

        /** @var Response $response */
        $response = $request->post($normalizedPath, $payload);

        $this->logOutgoing('post', $normalizedPath, $payload, $response);

        return $this->toObject($response);
    }

    /**
     * @param  array<string, mixed>  $payload
     * @param  array<string, string>  $headers
     */
    public function download(string $path, array $payload = [], string $method = 'post', array $headers = []): string
    {
        $request = $this->pendingRequest($headers)->accept('*/*');
        $normalizedPath = $this->normalizePath($path);
        $method = strtolower($method);

        /** @var Response $response */
        $response = match ($method) {
            'get' => $request->get($normalizedPath, $payload),
            default => $request->post($normalizedPath, $payload),
        };

        $this->logger?->outgoing(
            $method,
            $this->absoluteUrl($normalizedPath),
            $payload,
            $response->status(),
            '[binary body omitted]',
        );

        if ($response->failed()) {
            $body = $response->json();
            throw ApiRequestException::fromResponse(
                $response,
                is_array($body) ? $body : [],
            );
        }

        return $response->body();
    }

    /**
     * @param  array<string, mixed>  $payload
     * @param  array<string, mixed>  $query
     * @param  array<string, string>  $headers
     */
    protected function request(
        string $method,
        string $path,
        array $payload = [],
        array $query = [],
        array $headers = [],
    ): TapObject {
        $request = $this->pendingRequest($headers);
        $normalizedPath = $this->normalizePath($path);
        $method = strtolower($method);

        /** @var Response $response */
        $response = match ($method) {
            'get' => $request->get($normalizedPath, $query),
            'post' => $request->post($normalizedPath, $payload),
            'put' => $request->put($normalizedPath, $payload),
            'delete' => $request->delete($normalizedPath, $payload),
            default => throw new TapException("Unsupported HTTP method [{$method}]."),
        };

        $this->logOutgoing(
            $method,
            $normalizedPath,
            $method === 'get' ? $query : $payload,
            $response,
        );

        return $this->toObject($response);
    }

    /**
     * @param  array<string, string>  $headers
     */
    protected function pendingRequest(array $headers = []): PendingRequest
    {
        $request = Http::baseUrl(rtrim($this->baseUrl, '/').'/')
            ->withToken($this->secretKey)
            ->acceptJson()
            ->asJson()
            ->timeout($this->timeout)
            ->connectTimeout($this->connectTimeout);

        if ($this->retryTimes > 0) {
            $request = $request->retry(
                $this->retryTimes,
                $this->retrySleep,
                function (\Throwable $exception): bool {
                    if ($exception instanceof ConnectionException) {
                        return true;
                    }

                    return $exception instanceof RequestException
                        && $exception->response !== null
                        && $exception->response->serverError();
                },
                throw: false,
            );
        }

        if ($headers !== []) {
            $request = $request->withHeaders($headers);
        }

        return $request;
    }

    protected function normalizePath(string $path): string
    {
        return ltrim($path, '/');
    }

    protected function absoluteUrl(string $path): string
    {
        return rtrim($this->baseUrl, '/').'/'.ltrim($path, '/');
    }

    /**
     * @param  array<string, mixed>  $requestPayload
     */
    protected function logOutgoing(string $method, string $path, array $requestPayload, Response $response): void
    {
        $body = $response->json();

        $this->logger?->outgoing(
            $method,
            $this->absoluteUrl($path),
            $requestPayload,
            $response->status(),
            is_array($body) ? $body : ['body' => $response->body()],
        );
    }

    protected function toObject(Response $response): TapObject
    {
        $body = $response->json();

        if ($response->failed()) {
            throw ApiRequestException::fromResponse(
                $response,
                is_array($body) ? $body : [],
            );
        }

        return new TapObject(is_array($body) ? $body : []);
    }

    /**
     * @return array<string, string>
     */
    public static function idempotencyHeaders(?string $key): array
    {
        if ($key === null || $key === '') {
            return [];
        }

        return ['Idempotency-Key' => $key];
    }
}
