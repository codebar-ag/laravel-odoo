<?php

declare(strict_types=1);

return [
    'url' => env('LARAVEL_ODOO_URL', ''),
    'api_key' => env('LARAVEL_ODOO_API_KEY', ''),
    'db' => env('LARAVEL_ODOO_DB', null),

    /*
    |--------------------------------------------------------------------------
    | Maximum redirects
    |--------------------------------------------------------------------------
    |
    | The maximum number of HTTP redirects the connector will follow for a
    | single request (Guzzle's "allow_redirects.max").
    |
    */
    'max_redirects' => env('LARAVEL_ODOO_MAX_REDIRECTS', 5),

    /*
    |--------------------------------------------------------------------------
    | Response cache
    |--------------------------------------------------------------------------
    |
    | Read-only requests (search_read, health, version, ...) are cached through
    | Saloon's cache plugin. "driver" is any Laravel cache store and "lifetime"
    | the TTL in seconds. Set the lifetime to 0 to effectively disable caching.
    |
    */
    'cache' => [
        'driver' => env('LARAVEL_ODOO_CACHE_DRIVER', env('CACHE_STORE', 'file')),
        'lifetime_in_seconds' => env('LARAVEL_ODOO_CACHE_LIFETIME_IN_SECONDS', 60),
    ],
];
