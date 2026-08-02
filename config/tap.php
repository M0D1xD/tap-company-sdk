<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Tap API Credentials
    |--------------------------------------------------------------------------
    |
    | Secret key is required for server-side API calls. Public key is useful
    | for client-side SDKs. Merchant ID is attached by default when set.
    |
    */

    'secret_key' => env('TAP_SECRET_KEY'),

    'public_key' => env('TAP_PUBLIC_KEY'),

    'merchant_id' => env('TAP_MERCHANT_ID'),

    /*
    |--------------------------------------------------------------------------
    | API Base URL
    |--------------------------------------------------------------------------
    */

    'base_url' => env('TAP_BASE_URL', 'https://api.tap.company/v2/'),

    /*
    |--------------------------------------------------------------------------
    | HTTP Client
    |--------------------------------------------------------------------------
    */

    'timeout' => (int) env('TAP_TIMEOUT', 15),

    'connect_timeout' => (int) env('TAP_CONNECT_TIMEOUT', 5),

    'retry' => [
        'times' => (int) env('TAP_RETRY_TIMES', 2),
        'sleep' => (int) env('TAP_RETRY_SLEEP', 200),
    ],

    /*
    |--------------------------------------------------------------------------
    | Webhooks
    |--------------------------------------------------------------------------
    */

    'webhook' => [
        'enabled' => (bool) env('TAP_WEBHOOK_ENABLED', false),
        'path' => env('TAP_WEBHOOK_PATH', 'tap/webhook'),
        'middleware' => explode(',', (string) env('TAP_WEBHOOK_MIDDLEWARE', 'api')),
        'header' => env('TAP_WEBHOOK_HASH_HEADER', 'hashstring'),
    ],

];
