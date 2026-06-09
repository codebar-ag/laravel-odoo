<?php

use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\CallKw\Timesheets\GetTimesheetEntriesRequest;
use CodebarAg\Odoo\Responses\CallKw\Timesheets\TimesheetEntriesResponse;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('sends request to correct endpoint', function () {
    $mockClient = new MockClient([
        GetTimesheetEntriesRequest::class => MockResponse::fixture('CallKw/Timesheets/get_timesheet_entries'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'demo');
    $connector->withMockClient($mockClient);

    $response = TimesheetEntriesResponse::fromResponse(
        $connector->send(new GetTimesheetEntriesRequest)
    );

    $mockClient->assertSent(GetTimesheetEntriesRequest::class);
    expect($response->successful())->toBeTrue();
});

it('sends correct json-rpc body', function () {
    $mockClient = new MockClient([
        GetTimesheetEntriesRequest::class => MockResponse::fixture('CallKw/Timesheets/get_timesheet_entries'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'demo');
    $connector->withMockClient($mockClient);

    $connector->send(new GetTimesheetEntriesRequest);

    $mockClient->assertSent(function (GetTimesheetEntriesRequest $request) {
        $body = $request->body()->all();

        return $body['jsonrpc'] === '2.0'
            && $body['method'] === 'call'
            && $body['params']['model'] === 'account.analytic.line'
            && isset($body['params']['args'])
            && isset($body['params']['kwargs']);
    });
});

it('parses response correctly', function () {
    $mockClient = new MockClient([
        GetTimesheetEntriesRequest::class => MockResponse::fixture('CallKw/Timesheets/get_timesheet_entries'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'demo');
    $connector->withMockClient($mockClient);

    $response = TimesheetEntriesResponse::fromResponse(
        $connector->send(new GetTimesheetEntriesRequest)
    );

    $entries = $response->entries();

    expect($entries)->toHaveCount(1);
    expect($entries[0]->id)->toBe(42);
    expect($entries[0]->name)->toBe('Working on feature');
});
