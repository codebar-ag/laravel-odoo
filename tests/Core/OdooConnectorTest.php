<?php

declare(strict_types=1);

use CodebarAg\Odoo\OdooConnector;

it('uses the default timeout when none is provided', function () {
    $connector = new OdooConnector(baseUrl: 'https://demo.odoo.com');

    expect($connector->config()->get('timeout'))->toBe(15);
});

it('uses a custom timeout passed to the constructor', function () {
    $connector = new OdooConnector(baseUrl: 'https://demo.odoo.com', timeout: 60);

    expect($connector->config()->get('timeout'))->toBe(60);
});

it('applies timeout from laravel config via the service container', function () {
    config(['laravel-odoo.timeout' => 45]);

    /** @var OdooConnector $connector */
    $connector = app(OdooConnector::class);

    expect($connector->config()->get('timeout'))->toBe(45);
});
