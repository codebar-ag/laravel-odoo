<?php

use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\Api\Timesheets\GetTimesheetEntriesRequest;
use CodebarAg\Odoo\Responses\Api\Timesheets\TimesheetEntriesResponse;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('sends request to correct endpoint', function () {
    $mockClient = new MockClient([
        GetTimesheetEntriesRequest::class => MockResponse::fixture('Api/Timesheets/timesheet-entries'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = TimesheetEntriesResponse::fromResponse(
        $connector->send(new GetTimesheetEntriesRequest)
    );

    $mockClient->assertSent(GetTimesheetEntriesRequest::class);
    expect($response->successful())->toBeTrue();
});

it('sends correct body with domain and limit', function () {
    $mockClient = new MockClient([
        GetTimesheetEntriesRequest::class => MockResponse::fixture('Api/Timesheets/timesheet-entries'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $connector->send(new GetTimesheetEntriesRequest);

    $mockClient->assertSent(function (GetTimesheetEntriesRequest $request) {
        $body = $request->body()->all();

        return $body['domain'] === []
            && $body['limit'] === 100
            && in_array('name', $body['fields']);
    });
});

it('parses timesheet entries from response', function () {
    $mockClient = new MockClient([
        GetTimesheetEntriesRequest::class => MockResponse::fixture('Api/Timesheets/timesheet-entries'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = TimesheetEntriesResponse::fromResponse(
        $connector->send(new GetTimesheetEntriesRequest)
    );

    $entries = $response->entries();

    expect($entries)->toHaveCount(2);
    expect($entries[0]->id)->toBe(6);
    expect($entries[0]->projectId)->toBe(1);
    expect($entries[0]->projectName)->toBe('Internal');
    expect($entries[0]->unitAmount)->toBe(0.25);
});
