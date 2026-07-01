<?php

use CodebarAg\Odoo\Dto\Projects\CreateProjectDto;
use CodebarAg\Odoo\Dto\Tasks\CreateTaskDto;
use CodebarAg\Odoo\Dto\Timesheets\CreateTimesheetDto;
use CodebarAg\Odoo\Dto\Timesheets\UpdateTimesheetDto;
use CodebarAg\Odoo\Responses\Api\Timesheets\CreateTimesheetResponse;
use CodebarAg\Odoo\Responses\Api\Timesheets\TimesheetResponse;

it('creates , reads updates,reads and deletes a timesheetentry', function () {
    // A timesheet can only be booked on a task that belongs to a project — Odoo
    // rejects project-less ("private") tasks — so provision both first instead of
    // assuming fixed ids exist in the target instance.
    $projectId = $this->connector()->createProject(new CreateProjectDto(name: 'Timesheet Test Project'))->id();
    $taskId = $this->connector()->createTask(new CreateTaskDto(name: 'Timesheet Test Task', projectId: $projectId))->id();

    $dto = new CreateTimesheetDto(
        name: 'TestTimesheet',
        projectId: $projectId,
        taskId: $taskId,
        date: now()->toDateString(),
        unitAmount: 2.5,
        extraValues: []
    );

    $creationResponse = $this->connector()->createTimesheet($dto);

    expect($creationResponse)->not->toBeEmpty();
    expect($creationResponse)->toBeInstanceOf(CreateTimesheetResponse::class);
    expect($creationResponse->id())->toBeNumeric();

    $readCreatedTimesheetResponse = $this->connector()->readTimesheet($creationResponse->id());

    expect($readCreatedTimesheetResponse->dto()->name)->toBe('TestTimesheet');
    expect($readCreatedTimesheetResponse->dto()->unitAmount)->toBe(2.5);
    expect($readCreatedTimesheetResponse)->toBeInstanceOf(TimesheetResponse::class);

    $updateDto = new UpdateTimesheetDto(
        id: $creationResponse->id(),
        unitAmount: 4.5,
    );

    $updateTimesheetResponse = $this->connector()->updateTimesheet($updateDto);

    expect($updateTimesheetResponse->body())->toBe('true');

    $readUpdatedTimesheetResponse = $this->connector()->readTimesheet($creationResponse->id());

    expect($readUpdatedTimesheetResponse->dto()->name)->toBe('TestTimesheet');
    expect($readUpdatedTimesheetResponse->dto()->unitAmount)->toBe(4.5);
    expect($readUpdatedTimesheetResponse)->toBeInstanceOf(TimesheetResponse::class);

    $this->connector()->deleteTimesheet($creationResponse->id());
    $this->connector()->deleteTask($taskId);
    $this->connector()->deleteProject($projectId);

})->group('live', 'feature');
