<?php

use CodebarAg\Odoo\Facades\Odoo;
use CodebarAg\Odoo\OdooConnector;

it('can resolve odoo connector from the container', function () {
    $this->app->bind(OdooConnector::class, fn () => new OdooConnector('https://demo.odoo.com', db: 'demo'));

    $connector = $this->app->make(OdooConnector::class);

    expect($connector)->toBeInstanceOf(OdooConnector::class)
        ->and($connector->getDb())->toBe('demo');
});

it('resolves the connector from config without a manual binding', function () {
    config()->set('laravel-odoo.url', 'https://config.odoo.com');
    config()->set('laravel-odoo.api_key', 'cfg-key');
    config()->set('laravel-odoo.db', 'cfg-db');

    $connector = app(OdooConnector::class);

    expect($connector)->toBeInstanceOf(OdooConnector::class)
        ->and($connector->resolveBaseUrl())->toBe('https://config.odoo.com')
        ->and($connector->getApiKey())->toBe('cfg-key')
        ->and($connector->getDb())->toBe('cfg-db');
});

it('coalesces empty config credentials to null', function () {
    config()->set('laravel-odoo.url', 'https://config.odoo.com');
    config()->set('laravel-odoo.api_key', '');
    config()->set('laravel-odoo.db', '');

    $connector = app(OdooConnector::class);

    expect($connector->getApiKey())->toBeNull()
        ->and($connector->getDb())->toBeNull();
});

it('resolves through the Odoo facade', function () {
    config()->set('laravel-odoo.url', 'https://config.odoo.com');
    config()->set('laravel-odoo.db', 'facade-db');

    expect(Odoo::getDb())->toBe('facade-db');
});

it('applies a custom max redirects to the request config', function () {
    $connector = new OdooConnector('https://demo.odoo.com', maxRedirects: 9);

    $method = new ReflectionMethod($connector, 'defaultConfig');
    $config = $method->invoke($connector);

    expect(data_get($config, 'allow_redirects.max'))->toBe(9);
});

it('reads max redirects from config when resolved from the container', function () {
    config()->set('laravel-odoo.url', 'https://config.odoo.com');
    config()->set('laravel-odoo.max_redirects', 7);

    $connector = app(OdooConnector::class);

    $method = new ReflectionMethod($connector, 'defaultConfig');
    $config = $method->invoke($connector);

    expect(data_get($config, 'allow_redirects.max'))->toBe(7);
});

it('defaults max redirects to 5', function () {
    $connector = new OdooConnector('https://demo.odoo.com');

    $method = new ReflectionMethod($connector, 'defaultConfig');
    $config = $method->invoke($connector);

    expect(data_get($config, 'allow_redirects.max'))->toBe(5);
});
