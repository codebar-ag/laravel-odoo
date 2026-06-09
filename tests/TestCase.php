<?php

namespace CodebarAg\Odoo\Tests;

use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\OdooServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Saloon\Config;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Config::preventStrayRequests();
    }

    protected function getPackageProviders($app): array
    {
        return [
            OdooServiceProvider::class,
        ];
    }

    protected function connector()
    {
        return new OdooConnector(
            baseUrl: env('ODOO_URL'),
            apiKey: env('ODOO_API_KEY'),
            db: ($db = env('ODOO_DB')) !== '' ? $db : null,
        );
    }
}
