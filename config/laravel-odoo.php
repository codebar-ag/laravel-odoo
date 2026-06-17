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
];
