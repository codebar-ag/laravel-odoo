<?php

use CodebarAg\Odoo\Dto\Employees\EmployeeDto;

it('maps employee scalars and relation tuples', function () {
    $dto = EmployeeDto::fromArray([
        'id' => 1,
        'name' => 'John Doe',
        'work_email' => 'john.doe@example.com',
        'work_phone' => false,
        'mobile_phone' => false,
        'job_title' => 'Developer',
        'department_id' => [3, 'Research & Development'],
        'job_id' => [4, 'Developer'],
        'parent_id' => [5, 'Jane Manager'],
        'coach_id' => false,
        'user_id' => [2, 'Administrator'],
        'company_id' => [1, 'MyCompany'],
        'timesheet_manager_id' => [6, 'Tom Approver'],
        'last_validated_timesheet_date' => '2026-06-09',
        'active' => true,
    ]);

    expect($dto->id)->toBe(1)
        ->and($dto->name)->toBe('John Doe')
        ->and($dto->workEmail)->toBe('john.doe@example.com')
        ->and($dto->workPhone)->toBeNull()
        ->and($dto->mobilePhone)->toBeNull()
        ->and($dto->jobTitle)->toBe('Developer')
        ->and($dto->departmentId)->toBe(3)
        ->and($dto->departmentName)->toBe('Research & Development')
        ->and($dto->jobId)->toBe(4)
        ->and($dto->jobName)->toBe('Developer')
        ->and($dto->parentId)->toBe(5)
        ->and($dto->parentName)->toBe('Jane Manager')
        ->and($dto->coachId)->toBeNull()
        ->and($dto->userId)->toBe(2)
        ->and($dto->userName)->toBe('Administrator')
        ->and($dto->companyId)->toBe(1)
        ->and($dto->companyName)->toBe('MyCompany')
        ->and($dto->timesheetManagerId)->toBe(6)
        ->and($dto->timesheetManagerName)->toBe('Tom Approver')
        ->and($dto->lastValidatedTimesheetDate)->toBe('2026-06-09')
        ->and($dto->active)->toBeTrue();
});

it('falls back to null when fields are absent or false', function () {
    $dto = EmployeeDto::fromArray([
        'id' => 1,
        'name' => 'John Doe',
        'department_id' => false,
        'user_id' => false,
    ]);

    expect($dto->workEmail)->toBeNull()
        ->and($dto->jobTitle)->toBeNull()
        ->and($dto->departmentId)->toBeNull()
        ->and($dto->userId)->toBeNull()
        ->and($dto->timesheetManagerId)->toBeNull()
        ->and($dto->lastValidatedTimesheetDate)->toBeNull()
        ->and($dto->active)->toBeNull();
});
