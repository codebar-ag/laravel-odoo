<?php

namespace CodebarAg\Odoo\Tests\Live;

use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\OdooServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Saloon\Config;
use Saloon\Http\Faking\MockClient;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        MockClient::destroyGlobal();
        Config::allowStrayRequests();

        if (! env('LARAVEL_ODOO_URL') || ! env('LARAVEL_ODOO_API_KEY')) {
            $this->markTestSkipped('Live tests require LARAVEL_ODOO_URL and LARAVEL_ODOO_API_KEY to be set in phpunit.xml');
        }
    }

    protected function getPackageProviders($app): array
    {
        return [
            OdooServiceProvider::class,
        ];
    }

    public function connector(): OdooConnector
    {
        return $this->connectorForKey(env('LARAVEL_ODOO_API_KEY'));
    }

    /**
     * Build a connector for an explicit API key (permission tier).
     *
     * Lets the suite exercise the same operations against several Odoo users
     * (administrator, "Benutzer", no-permission) instead of only the admin key.
     */
    public function connectorForKey(?string $apiKey): OdooConnector
    {
        return new OdooConnector(
            baseUrl: env('LARAVEL_ODOO_URL'),
            apiKey: $apiKey,
            db: ($db = env('LARAVEL_ODOO_DB')) !== '' ? $db : null,
        );
    }
}
