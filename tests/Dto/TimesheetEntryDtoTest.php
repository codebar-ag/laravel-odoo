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
