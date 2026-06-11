<?php

use CodebarAg\Odoo\Dto\Timesheets\CreateTimesheetDto;
use CodebarAg\Odoo\Dto\Timesheets\UpdateTimesheetDto;
use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Responses\OdooResponse;

/**
 * (model, operation) cells checked for every permission tier.
 *
 * Read is checked for several models; the write/create/unlink cells use the only
 * model the connector can mutate non-destructively (account.analytic.line).
 */
dataset('odoo_access_matrix', [
    'analytic line · read' => ['account.analytic.line', 'read'],
    'analytic line · create' => ['account.analytic.line', 'create'],
    'analytic line · write' => ['account.analytic.line', 'write'],
    'analytic line · unlink' => ['account.analytic.line', 'unlink'],
    'project · read' => ['project.project', 'read'],
    'task · read' => ['project.task', 'read'],
    'employee · read' => ['hr.employee', 'read'],
    'user · read' => ['res.users', 'read'],
]);

/** Non-existent id used to probe denial without mutating real records. */
const ODOO_PHANTOM_ID = 999999999;

it('has_access agrees with the real operation outcome for each tier', function (string $tier, string $env, string $model, string $operation) {
    $connector = odooTierConnector($tier, $env);

    $permissions = $connector->getPermissions($model, $operation);

    // The has_access call itself must always degrade gracefully.
    assertOdooResponseResilient($permissions);

    // If the has_access call failed we cannot derive an expectation; the
    // resilience assertion above is the meaningful check for that case.
    if ($permissions->failed()) {
        return;
    }

    $allowed = $permissions->allowed();

    if ($operation === 'read') {
        // Read is non-destructive, so we can cross-check against a real call.
        // The sound invariant is one-directional: a model-level denial is
        // authoritative — the real read MUST also be denied. The reverse does
        // NOT hold: has_access can report model-level read access while record
        // rules or field-level security still deny the actual read (e.g.
        // res.users for an unprivileged user), so we never assert success here.
        $read = realReadFor($connector, $model);
        assertOdooResponseResilient($read);

        if (! $allowed) {
            expect(odooResponseDenied($read))->toBeTrue("read on {$model} should be denied for tier [{$tier}] (model access denied)");
        }

        return;
    }

    // Mutating operations: only probe when has_access says DENIED, since a
    // denied attempt changes nothing. When allowed, the admin CRUD test
    // (LiveTimesheetTest) covers the real mutation.
    if (! $allowed) {
        $attempt = realDeniedProbeFor($connector, $operation);
        assertOdooResponseResilient($attempt);
        expect(odooResponseDenied($attempt))->toBeTrue("{$operation} on {$model} should be denied for tier [{$tier}]");
    }
})
    ->with('odoo_permission_tiers')
    ->with('odoo_access_matrix')
    ->group('live');

/** A real read call for the given model, used to cross-check read access. */
function realReadFor(OdooConnector $connector, string $model): OdooResponse
{
    return match ($model) {
        'account.analytic.line' => $connector->getTimesheetEntries(limit: 1),
        'project.project' => $connector->getProjects(limit: 1),
        'project.task' => $connector->getAllTasks(limit: 1),
        'hr.employee' => $connector->getEmployeeByUserId(1),
        'res.users' => $connector->getUser(),
    };
}

/**
 * A real mutating call (on account.analytic.line) against a phantom id, used to
 * confirm that a tier without access is denied rather than silently mutating.
 */
function realDeniedProbeFor(OdooConnector $connector, string $operation): OdooResponse
{
    return match ($operation) {
        'create' => $connector->createTimesheet(new CreateTimesheetDto(
            name: 'perm-probe — should be denied',
            projectId: 1,
            taskId: 1,
            date: now()->toDateString(),
            unitAmount: 0.25,
            employeeId: 1,
        )),
        'write' => $connector->updateTimesheet(new UpdateTimesheetDto(
            id: ODOO_PHANTOM_ID,
            unitAmount: 0.25,
        )),
        'unlink' => $connector->deleteTimesheet(ODOO_PHANTOM_ID),
    };
}
