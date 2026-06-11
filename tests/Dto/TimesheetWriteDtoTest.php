<?php

use CodebarAg\Odoo\Dto\Timesheets\CreateTimesheetDto;
use CodebarAg\Odoo\Dto\Timesheets\UpdateTimesheetDto;

it('serializes a create dto and merges extra values', function () {
    $dto = new CreateTimesheetDto(
        name: 'Test entry',
        projectId: 1,
        taskId: 2,
        date: '2026-06-10',
        unitAmount: 1.5,
        extraValues: ['x_studio_custom' => 'abc'],
    );

    expect($dto->toArray())->toBe([
        'name' => 'Test entry',
        'project_id' => 1,
        'task_id' => 2,
        'date' => '2026-06-10',
        'unit_amount' => 1.5,
        'x_studio_custom' => 'abc',
    ]);
});

it('omits the optional employee id on create when null', function () {
    $dto = new CreateTimesheetDto(
        name: 'Test entry',
        projectId: 1,
        taskId: 2,
        date: '2026-06-10',
        unitAmount: 1.5,
    );

    expect($dto->toArray())->not->toHaveKey('employee_id');
});

it('only serializes provided fields on update', function () {
    $dto = new UpdateTimesheetDto(
        id: 9,
        unitAmount: 2.0,
        extraValues: ['x_studio_note' => 'hi'],
    );

    expect($dto->toArray())->toBe([
        'unit_amount' => 2.0,
        'x_studio_note' => 'hi',
    ]);
});
