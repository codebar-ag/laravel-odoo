<?php

use CodebarAg\Odoo\Dto\Timesheets\CreateTimesheetDto;

it('handles a timesheet write attempt gracefully for each tier', function (string $tier, string $env) {
    $connector = odooTierConnector($tier, $env);

    $dto = new CreateTimesheetDto(
        name: 'perm-test — safe to delete',
        projectId: 1,
        taskId: 1,
        date: now()->toDateString(),
        unitAmount: 0.25,
        employeeId: 1,
        extraValues: [],
    );

    $response = $connector->createTimesheet($dto);

    assertOdooResponseResilient($response);

    if ($response->successful() && is_int($response->id())) {
        // Tier is allowed to write — verify and clean up so no stray data is left.
        expect($response->id())->toBeNumeric();

        $connector->deleteTimesheet($response->id());
    } else {
        // Tier is denied — the denial must be reported, not thrown.
        expect(odooResponseDenied($response))->toBeTrue();
    }
})
    ->with('odoo_permission_tiers')
    ->group('live', 'feature');
