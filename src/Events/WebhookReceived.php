<?php

declare(strict_types=1);

namespace TapCompany\LaravelSdk\Events;

use TapCompany\LaravelSdk\Data\TapObject;

abstract class WebhookReceived
{
    public function __construct(public readonly TapObject $payload)
    {
    }
}
