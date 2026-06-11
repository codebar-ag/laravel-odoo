<?php

use CodebarAg\Odoo\Dto\Timesheets\CreateTimesheetDto;
use CodebarAg\Odoo\Dto\Timesheets\UpdateTimesheetDto;
use CodebarAg\Odoo\Responses\Api\Timesheets\CreateTimesheetResponse;
use CodebarAg\Odoo\Responses\Api\Timesheets\TimesheetResponse;

it('creates , reads updates,reads and deletes a timesheetentry', function () {

    $dto = new CreateTimesheetDto(
        name: 'TestTimesheet',
        projectId: 1,
        taskId: 1,
        date: now()->toDateString(),
        unitAmount: 2.5,
        employeeId: 1,
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

    $deleteTimesheetEntry = $this->connector()->deleteTimesheet($creationResponse->id());

})->group('live', 'feature');
