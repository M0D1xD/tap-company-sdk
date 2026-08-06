<?php

declare(strict_types=1);

namespace TapCompany\LaravelSdk\Enums;

/**
 * `status` values returned on a Tap charge object.
 *
 * @see https://developers.tap.company/reference/charges.md
 */
enum ChargeStatus: string
{
    case INITIATED = 'INITIATED';
    case ABANDONED = 'ABANDONED';
    case CANCELLED = 'CANCELLED';
    case FAILED = 'FAILED';
    case DECLINED = 'DECLINED';
    case RESTRICTED = 'RESTRICTED';
    case CAPTURED = 'CAPTURED';
    case VOID = 'VOID';
    case TIMEDOUT = 'TIMEDOUT';
    case UNKNOWN = 'UNKNOWN';

    public function isSuccessful(): bool
    {
        return $this === self::CAPTURED;
    }

    public function isTerminal(): bool
    {
        return in_array($this, [
            self::CAPTURED,
            self::CANCELLED,
            self::FAILED,
            self::DECLINED,
            self::RESTRICTED,
            self::VOID,
            self::TIMEDOUT,
            self::ABANDONED,
        ], true);
    }
}
