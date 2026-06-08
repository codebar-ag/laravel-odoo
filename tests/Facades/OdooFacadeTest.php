<?php

use CodebarAg\Odoo\OdooConnector;

it('can resolve odoo connector from the container', function () {
    $this->app->bind(OdooConnector::class, fn () => new OdooConnector('https://demo.odoo.com', 'demo'));

    $connector = $this->app->make(OdooConnector::class);

    expect($connector)->toBeInstanceOf(OdooConnector::class)
        ->and($connector->getDb())->toBe('demo');
});
