<?php

use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\Api\Projects\GetProjectsRequest;
use CodebarAg\Odoo\Requests\Api\Tasks\GetAllTasksRequest;
use CodebarAg\Odoo\Requests\Api\Timesheets\GetTimesheetEntriesRequest;
use CodebarAg\Odoo\Responses\Api\Projects\ProjectsResponse;
use CodebarAg\Odoo\Responses\Api\Tasks\TasksResponse;
use CodebarAg\Odoo\Responses\Api\Timesheets\TimesheetEntriesResponse;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('aggregates projects, tasks and timesheets into a keyed result', function () {
    $mockClient = new MockClient([
        GetProjectsRequest::class => MockResponse::fixture('Api/Projects/projects'),
        GetAllTasksRequest::class => MockResponse::fixture('Api/Tasks/tasks'),
        GetTimesheetEntriesRequest::class => MockResponse::fixture('Api/Timesheets/timesheet-entries'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $result = $connector->syncAll();

    expect($result)->toHaveKeys(['projects', 'tasks', 'timesheets'])
        ->and($result['projects'])->toBeInstanceOf(ProjectsResponse::class)
        ->and($result['tasks'])->toBeInstanceOf(TasksResponse::class)
        ->and($result['timesheets'])->toBeInstanceOf(TimesheetEntriesResponse::class);

    $mockClient->assertSent(GetProjectsRequest::class);
    $mockClient->assertSent(GetAllTasksRequest::class);
    $mockClient->assertSent(GetTimesheetEntriesRequest::class);
});
