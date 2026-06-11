<?php

use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\Api\Timesheets\ReadTimesheetRequest;
use CodebarAg\Odoo\Responses\Api\Timesheets\TimesheetResponse;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('sends request to correct endpoint', function () {
    $mockClient = new MockClient([
        ReadTimesheetRequest::class => MockResponse::fixture('Api/Timesheets/timesheet'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = TimesheetResponse::fromResponse(
        $connector->send(new ReadTimesheetRequest(6))
    );

    $mockClient->assertSent(ReadTimesheetRequest::class);
    expect($response->successful())->toBeTrue();
});

it('sends correct domain filtering by id', function () {
    $mockClient = new MockClient([
        ReadTimesheetRequest::class => MockResponse::fixture('Api/Timesheets/timesheet'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $connector->send(new ReadTimesheetRequest(42));

    $mockClient->assertSent(function (ReadTimesheetRequest $request) {
        $body = $request->body()->all();

        return data_get($body, 'domain') === [['id', '=', 42]];
    });
});

it('parses single timesheet entry from response', function () {
    $mockClient = new MockClient([
        ReadTimesheetRequest::class => MockResponse::fixture('Api/Timesheets/timesheet'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = TimesheetResponse::fromResponse(
        $connector->send(new ReadTimesheetRequest(6))
    );

    $entry = $response->dto();

    expect($entry)->not->toBeNull();
    expect($entry->id)->toBe(6);
    expect($entry->unitAmount)->toBe(0.25);
    expect($entry->date)->toBe('2026-06-09');
    expect($entry->projectId)->toBe(1);
    expect($entry->projectName)->toBe('Internal');
    expect($entry->taskName)->toBe('Meeting');
    expect($entry->validated)->toBeTrue();
    expect($entry->validatedStatus)->toBe('validated');
    expect($entry->readonlyTimesheet)->toBeTrue();
    expect($entry->userName)->toBe('Administrator');
    expect($entry->companyName)->toBe('MyCompany');
});
