<?php

declare(strict_types=1);

use CodebarAg\Odoo\OdooConnector;
use Saloon\Exceptions\Request\FatalRequestException;

it('respects a custom timeout on live requests', function () {
    $connector = new OdooConnector(
        baseUrl: env('LARAVEL_ODOO_URL'),
        apiKey: env('LARAVEL_ODOO_API_KEY'),
        db: ($db = env('LARAVEL_ODOO_DB')) !== '' ? $db : null,
        timeout: 5,
    );

    $response = $connector->health();

    expect($response->successful())->toBeTrue()
        ->and($connector->config()->get('timeout'))->toBe(5);
})->group('live');

it('throws when the timeout is exceeded', function () {
    $connector = new OdooConnector(
        baseUrl: 'http://10.255.255.1',
        timeout: 1,
    );

    $connector->health();
})->throws(FatalRequestException::class)->group('live');
