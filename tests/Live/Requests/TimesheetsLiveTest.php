<?php

use CodebarAg\Odoo\Dto\CallKw\Timesheets\CreateTimesheetDto;
use CodebarAg\Odoo\Dto\CallKw\Timesheets\UpdateTimesheetDto;
use CodebarAg\Odoo\Responses\Api\Timesheets\CreateTimesheetResponse;
use CodebarAg\Odoo\Responses\Api\Timesheets\TimesheetEntriesResponse;
use CodebarAg\Odoo\Responses\Api\Timesheets\MutateTimesheetResponse;
use CodebarAg\Odoo\Responses\Api\Timesheets\TimesheetResponse;
it('gets timesheet entries', function () {
    $response = TimesheetEntriesResponse::fromResponse(
        $this->connector()->getTimesheetEntries(limit: 5)
    );

    expect($response->successful())->toBeTrue()
        ->and($response->body())->toBeJson();
})->group('live');

it('gets timesheet entries from last 30 days', function () {
    $response = TimesheetEntriesResponse::fromResponse(
        $this->connector()->getTimesheetEntriesLastDays(days: 30)
    );

    expect($response->successful())->toBeTrue()
        ->and($response->body())->toBeJson();
})->group('live');
