<?php

use CodebarAg\Odoo\OdooConnector;

/**
 * Read-only Odoo operations exercised against every permission tier.
 *
 * Operation names (not closures) are used so Pest does not treat them as lazy
 * dataset providers; the closure dispatch happens inside the test body.
 */
dataset('odoo_read_operations', [
    'getUser',
    'getProjects',
    'getAllTasks',
    'getTimesheetEntries',
    'getFields',
    'getPermissions',
]);

it('returns a graceful structured response for each read operation and tier', function (string $tier, string $env, string $operation) {
    $connector = odooTierConnector($tier, $env);

    $response = match ($operation) {
        'getUser' => $connector->getUser(),
        'getProjects' => $connector->getProjects(),
        'getAllTasks' => $connector->getAllTasks(),
        'getTimesheetEntries' => $connector->getTimesheetEntries(),
        'getFields' => $connector->getFields('account.analytic.line'),
        'getPermissions' => $connector->getPermissions('account.analytic.line', 'read'),
    };

    // The SDK must never throw for a privilege-related failure — a denied call
    // is just a failed, still-readable response.
    assertOdooResponseResilient($response);
})
    ->with('odoo_permission_tiers')
    ->with('odoo_read_operations')
    ->group('live');

it('authenticates and reads successfully for the admin tier', function (string $operation) {
    $connector = (new OdooConnector(
        baseUrl: env('LARAVEL_ODOO_URL'),
        apiKey: env('LARAVEL_ODOO_API_KEY'),
        db: ($db = env('LARAVEL_ODOO_DB')) !== '' ? $db : null,
    ));

    $response = match ($operation) {
        'getUser' => $connector->getUser(),
        'getProjects' => $connector->getProjects(),
        'getAllTasks' => $connector->getAllTasks(),
        'getTimesheetEntries' => $connector->getTimesheetEntries(),
        'getFields' => $connector->getFields('account.analytic.line'),
        'getPermissions' => $connector->getPermissions('account.analytic.line', 'read'),
    };

    expect($response->successful())->toBeTrue()
        ->and($response->body())->toBeJson();
})
    ->with('odoo_read_operations')
    ->group('live');
