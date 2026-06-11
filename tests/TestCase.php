<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Tests;

use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\OdooServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Saloon\Config;
use Spatie\LaravelData\LaravelDataServiceProvider;

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
            LaravelDataServiceProvider::class,
            OdooServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        // Use the in-memory array store so cached responses never leak between tests.
        $app['config']->set('laravel-odoo.cache.driver', 'array');
    }

    protected function connector(): OdooConnector
    {
        return new OdooConnector(
            baseUrl: env('LARAVEL_ODOO_URL'),
            apiKey: env('LARAVEL_ODOO_API_KEY'),
            db: ($db = env('LARAVEL_ODOO_DB')) !== '' ? $db : null,
        );
    }
}
