<?php

use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Responses\OdooResponse;
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
 * Permission tiers the Live suite runs against.
 *
 * Each entry is [tier label, env var holding that user's API key]. A tier whose
 * env var is empty is skipped (see odooTierConnector) so the suite still runs
 * with only the administrator key configured.
 *
 * @see tests/Live/Permissions
 */
dataset('odoo_permission_tiers', [
    'admin' => ['admin', 'LARAVEL_ODOO_API_KEY'],
    'benutzer' => ['benutzer', 'LARAVEL_ODOO_API_KEY_USER'],
    'none' => ['none', 'LARAVEL_ODOO_API_KEY_NONE'],
]);

/**
 * Resolve a connector for a permission tier, skipping the test when the tier's
 * API key is not configured in phpunit.xml.
 */
function odooTierConnector(string $tier, string $envName): OdooConnector
{
    $key = env($envName);

    if (! $key) {
        test()->markTestSkipped("Permission tier [{$tier}] requires {$envName} to be set in phpunit.xml");
    }

    return test()->connectorForKey($key);
}

/**
 * Whether an Odoo response represents a permission denial (AccessError) rather
 * than a successful call.
 *
 * Odoo surfaces access errors as a failed HTTP response carrying a readable
 * error message; we treat any failed response as denied. Refine the predicate
 * (e.g. status() === 403 or a message match) once the live denial shape is
 * confirmed — see tests/Live/Permissions/OperationResilienceLiveTest.php.
 */
function odooResponseDenied(OdooResponse $response): bool
{
    return $response->failed();
}

/**
 * Assert that an Odoo response degraded gracefully: the SDK returned a typed,
 * structured response (never threw) and its accessors are safe to read whether
 * the call succeeded or was denied.
 */
function assertOdooResponseResilient(OdooResponse $response): void
{
    expect($response)->toBeInstanceOf(OdooResponse::class)
        ->and($response->status())->toBeInt()
        ->and($response->body())->toBeString();

    // Accessors must not throw regardless of outcome.
    $error = $response->error();
    expect($error === null || is_string($error))->toBeTrue();

    if ($response->successful()) {
        expect($response->body())->toBeJson();
    }
}

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
