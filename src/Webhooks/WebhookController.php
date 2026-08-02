<?php

declare(strict_types=1);

namespace TapCompany\LaravelSdk\Webhooks;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use TapCompany\LaravelSdk\Events\AuthorizeUpdated;
use TapCompany\LaravelSdk\Events\ChargeUpdated;
use TapCompany\LaravelSdk\Events\InvoiceUpdated;
use TapCompany\LaravelSdk\Events\PayoutUpdated;
use TapCompany\LaravelSdk\Events\RefundUpdated;
use TapCompany\LaravelSdk\Events\TapWebhookReceived;
use TapCompany\LaravelSdk\Exceptions\InvalidWebhookSignatureException;

class WebhookController extends Controller
{
    public function __construct(protected SignatureValidator $validator)
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        /** @var array<string, mixed> $payload */
        $payload = $request->all();
        $header = (string) config('tap.webhook.header', 'hashstring');
        $hash = $request->header($header) ?? $request->header(ucfirst($header));

        try {
            $object = $this->validator->validateOrFail($payload, is_string($hash) ? $hash : null);
        } catch (InvalidWebhookSignatureException $exception) {
            return response()->json(['message' => $exception->getMessage()], 400);
        }

        event(new TapWebhookReceived($object));

        $type = (string) ($payload['object'] ?? '');

        match ($type) {
            'charge' => event(new ChargeUpdated($object)),
            'authorize' => event(new AuthorizeUpdated($object)),
            'refund' => event(new RefundUpdated($object)),
            'invoice' => event(new InvoiceUpdated($object)),
            'payout' => event(new PayoutUpdated($object)),
            default => null,
        };

        return response()->json(['received' => true]);
    }
}
