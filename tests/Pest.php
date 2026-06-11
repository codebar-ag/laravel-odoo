<?php

use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Tests\Live\TestCase as LiveTestCase;
use CodebarAg\Odoo\Tests\TestCase;
use Saloon\Config;
use Saloon\Http\Faking\Fixture;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

uses(TestCase::class)
    ->afterEach(function () {
        Saloon::fake([]);
    })
    ->in('Core', 'Dto', 'Facades', 'Requests', 'Responses');

uses(LiveTestCase::class)->in('Live');

/**
 * Helper function to check if fixtures should be reset/regenerated.
 * Set RESET_FIXTURES=true in phpunit.xml to regenerate fixtures from live API.
 * Defaults to false (use existing fixtures).
 */
function shouldResetFixtures(): bool
{
    return filter_var(getenv('RESET_FIXTURES') ?: false, FILTER_VALIDATE_BOOLEAN);
}

/**
 * Build an OdooConnector + MockClient for a request → fixture map.
 *
 * Default (RESET_FIXTURES=false): the stored fixtures are replayed and no
 * network call is made — the connector credentials are irrelevant.
 *
 * Recording (RESET_FIXTURES=true): the named fixture files are deleted and the
 * requests are sent for real against the live Odoo instance (credentials from
 * env), then Saloon stores the responses as the new fixtures.
 *
 * @param  array<class-string, string>  $fixtures  Map of request class => fixture name (e.g. 'Session/version')
 * @return array{0: OdooConnector, 1: MockClient}
 */
function odooMockClient(array $fixtures): array
{
    $reset = shouldResetFixtures();

    if ($reset) {
        foreach ($fixtures as $name) {
            $path = __DIR__.'/Fixtures/Saloon/'.$name.'.json';

            if (is_file($path)) {
                unlink($path);
            }
        }

        // Recording needs the real request to leave the test process.
        Config::allowStrayRequests();
    }

    $mockClient = new MockClient(
        array_map(fn (string $name): Fixture => MockResponse::fixture($name), $fixtures)
    );

    $connector = $reset
        ? new OdooConnector(
            baseUrl: env('LARAVEL_ODOO_URL'),
            apiKey: env('LARAVEL_ODOO_API_KEY'),
            db: ($db = env('LARAVEL_ODOO_DB')) !== '' ? $db : null,
        )
        : new OdooConnector('https://demo.odoo.com', 'api-key-123');

    $connector->withMockClient($mockClient);

    return [$connector, $mockClient];
}
