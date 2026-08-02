<?php

declare(strict_types=1);

namespace TapCompany\LaravelSdk\Support;

class Currency
{
    /**
     * Currencies that use 3 decimal places per Tap / ISO conventions.
     *
     * @var list<string>
     */
    public const THREE_DECIMAL = ['BHD', 'IQD', 'JOD', 'KWD', 'LYD', 'OMR', 'TND'];

    public static function decimalPlaces(string $currency): int
    {
        return in_array(strtoupper($currency), self::THREE_DECIMAL, true) ? 3 : 2;
    }

    public static function formatAmount(float|int|string $amount, string $currency): string
    {
        $places = self::decimalPlaces($currency);

        return number_format((float) $amount, $places, '.', '');
    }
}
