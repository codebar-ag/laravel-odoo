<?php

use CodebarAg\Odoo\Dto\Timesheets\CreateTimesheetDto;
use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\Api\Timesheets\CreateTimesheetRequest;
use CodebarAg\Odoo\Responses\Api\Timesheets\CreateTimesheetResponse;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('sends request to correct endpoint', function () {
    $mockClient = new MockClient([
        CreateTimesheetRequest::class => MockResponse::fixture('Api/Timesheets/create-timesheet'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $dto = new CreateTimesheetDto(
        name: 'Test entry',
        projectId: 1,
        taskId: 2,
        date: '2026-06-10',
        unitAmount: 1.5,
    );

    $response = CreateTimesheetResponse::fromResponse(
        $connector->send(new CreateTimesheetRequest($dto))
    );

    $mockClient->assertSent(CreateTimesheetRequest::class);
    expect($response->successful())->toBeTrue();
});

it('sends correct vals_list body', function () {
    $mockClient = new MockClient([
        CreateTimesheetRequest::class => MockResponse::fixture('Api/Timesheets/create-timesheet'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $dto = new CreateTimesheetDto(
        name: 'Test entry',
        projectId: 1,
        taskId: 2,
        date: '2026-06-10',
        unitAmount: 1.5,
        employeeId: 3,
    );

    $connector->send(new CreateTimesheetRequest($dto));

    $mockClient->assertSent(function (CreateTimesheetRequest $request) {
        $body = $request->body()->all();

        return data_get($body, 'vals_list.name') === 'Test entry'
            && data_get($body, 'vals_list.project_id') === 1
            && data_get($body, 'vals_list.task_id') === 2
            && data_get($body, 'vals_list.unit_amount') === 1.5
            && data_get($body, 'vals_list.employee_id') === 3;
    });
});

it('returns the created timesheet id', function () {
    $mockClient = new MockClient([
        CreateTimesheetRequest::class => MockResponse::fixture('Api/Timesheets/create-timesheet'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $dto = new CreateTimesheetDto(
        name: 'Test entry',
        projectId: 1,
        taskId: 2,
        date: '2026-06-10',
        unitAmount: 1.5,
    );

    $response = CreateTimesheetResponse::fromResponse(
        $connector->send(new CreateTimesheetRequest($dto))
    );

    expect($response->id())->toBe(9);
});
