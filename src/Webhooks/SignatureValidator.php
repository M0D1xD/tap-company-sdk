<?php

declare(strict_types=1);

namespace TapCompany\LaravelSdk\Webhooks;

use TapCompany\LaravelSdk\Data\TapObject;
use TapCompany\LaravelSdk\Exceptions\InvalidWebhookSignatureException;
use TapCompany\LaravelSdk\Support\Currency;

class SignatureValidator
{
    public function __construct(protected string $secretKey)
    {
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function compute(array $payload): string
    {
        $object = (string) ($payload['object'] ?? '');

        $toBeHashed = match ($object) {
            'invoice' => $this->invoiceString($payload),
            default => $this->paymentString($payload),
        };

        return hash_hmac('sha256', $toBeHashed, $this->secretKey);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function isValid(array $payload, ?string $providedHash): bool
    {
        if ($providedHash === null || $providedHash === '') {
            return false;
        }

        return hash_equals($this->compute($payload), $providedHash);
    }

    /**
     * @param  array<string, mixed>  $payload
     *
     * @throws InvalidWebhookSignatureException
     */
    public function validateOrFail(array $payload, ?string $providedHash): TapObject
    {
        if (! $this->isValid($payload, $providedHash)) {
            throw new InvalidWebhookSignatureException;
        }

        return new TapObject($payload);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    protected function paymentString(array $payload): string
    {
        $currency = (string) ($payload['currency'] ?? '');
        $amount = Currency::formatAmount($payload['amount'] ?? 0, $currency);

        $id = (string) ($payload['id'] ?? '');
        $gatewayReference = (string) data_get($payload, 'reference.gateway', '');
        $paymentReference = (string) data_get($payload, 'reference.payment', '');
        $status = (string) ($payload['status'] ?? '');
        $created = (string) (
            data_get($payload, 'transaction.created')
            ?? data_get($payload, 'created')
            ?? ''
        );

        return 'x_id'.$id
            .'x_amount'.$amount
            .'x_currency'.$currency
            .'x_gateway_reference'.$gatewayReference
            .'x_payment_reference'.$paymentReference
            .'x_status'.$status
            .'x_created'.$created;
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    protected function invoiceString(array $payload): string
    {
        $currency = (string) ($payload['currency'] ?? '');
        $amount = Currency::formatAmount($payload['amount'] ?? 0, $currency);

        $id = (string) ($payload['id'] ?? '');
        $updated = (string) ($payload['updated'] ?? '');
        $status = (string) ($payload['status'] ?? '');
        $created = (string) ($payload['created'] ?? '');

        return 'x_id'.$id
            .'x_amount'.$amount
            .'x_currency'.$currency
            .'x_updated'.$updated
            .'x_status'.$status
            .'x_created'.$created;
    }
}
