<?php

declare(strict_types=1);

namespace TapCompany\LaravelSdk\Exceptions;

class InvalidWebhookSignatureException extends TapException
{
    public function __construct(string $message = 'Invalid Tap webhook signature.')
    {
        parent::__construct($message, 400);
    }
}
