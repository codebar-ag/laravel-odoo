<?php

use CodebarAg\Odoo\Dto\Timesheets\TimesheetEntryDto;

it('maps relation tuples and coerces unit amount to float', function () {
    $dto = TimesheetEntryDto::fromArray([
        'id' => 11,
        'name' => 'Worked',
        'project_id' => [1, 'Internal'],
        'task_id' => [2, 'Meeting'],
        'unit_amount' => '1.5',
        'date' => '2026-06-10',
        'employee_id' => [3, 'Alice'],
    ]);

    expect($dto->id)->toBe(11)
        ->and($dto->projectId)->toBe(1)
        ->and($dto->projectName)->toBe('Internal')
        ->and($dto->taskId)->toBe(2)
        ->and($dto->taskName)->toBe('Meeting')
        ->and($dto->unitAmount)->toBe(1.5)
        ->and($dto->date)->toBe('2026-06-10')
        ->and($dto->employeeId)->toBe(3)
        ->and($dto->employeeName)->toBe('Alice');
});

it('falls back when relations are false and amount missing', function () {
    $dto = TimesheetEntryDto::fromArray([
        'id' => 1,
        'name' => 'X',
        'project_id' => false,
        'task_id' => false,
        'employee_id' => false,
    ]);

    expect($dto->projectId)->toBeNull()
        ->and($dto->taskId)->toBeNull()
        ->and($dto->employeeId)->toBeNull()
        ->and($dto->unitAmount)->toBe(0.0)
        ->and($dto->date)->toBe('');
});

it('maps the approval / lock status and other detail fields', function () {
    $dto = TimesheetEntryDto::fromArray([
        'id' => 6,
        'name' => 'Worked',
        'date' => '2026-06-09',
        'unit_amount' => 0.25,
        'user_id' => [2, 'Administrator'],
        'validated' => true,
        'validated_status' => 'validated',
        'user_can_validate' => true,
        'readonly_timesheet' => true,
        'amount' => -25.0,
        'company_id' => [1, 'MyCompany'],
        'partner_id' => [4, 'ACME'],
        'create_date' => '2026-06-09 09:30:00',
        'write_date' => '2026-06-09 10:00:00',
    ]);

    expect($dto->userId)->toBe(2)
        ->and($dto->userName)->toBe('Administrator')
        ->and($dto->validated)->toBeTrue()
        ->and($dto->validatedStatus)->toBe('validated')
        ->and($dto->userCanValidate)->toBeTrue()
        ->and($dto->readonlyTimesheet)->toBeTrue()
        ->and($dto->amount)->toBe(-25.0)
        ->and($dto->companyId)->toBe(1)
        ->and($dto->companyName)->toBe('MyCompany')
        ->and($dto->partnerId)->toBe(4)
        ->and($dto->partnerName)->toBe('ACME')
        ->and($dto->createDate)->toBe('2026-06-09 09:30:00')
        ->and($dto->writeDate)->toBe('2026-06-09 10:00:00');
});

it('leaves new fields null when absent (e.g. permission-gated)', function () {
    $dto = TimesheetEntryDto::fromArray([
        'id' => 1,
        'name' => 'X',
        'date' => '2026-06-09',
    ]);

    expect($dto->userId)->toBeNull()
        ->and($dto->validated)->toBeNull()
        ->and($dto->validatedStatus)->toBeNull()
        ->and($dto->userCanValidate)->toBeNull()
        ->and($dto->readonlyTimesheet)->toBeNull()
        ->and($dto->amount)->toBeNull()
        ->and($dto->companyId)->toBeNull()
        ->and($dto->partnerId)->toBeNull()
        ->and($dto->createDate)->toBeNull()
        ->and($dto->writeDate)->toBeNull();
});
