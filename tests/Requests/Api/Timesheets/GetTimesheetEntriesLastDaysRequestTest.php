<?php

use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\Api\Timesheets\GetTimesheetEntriesLastDaysRequest;
use CodebarAg\Odoo\Responses\Api\Timesheets\TimesheetEntriesResponse;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('sends request to correct endpoint', function () {
    $mockClient = new MockClient([
        GetTimesheetEntriesLastDaysRequest::class => MockResponse::fixture('Api/Timesheets/timesheet-entries-last-days'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = TimesheetEntriesResponse::fromResponse(
        $connector->send(new GetTimesheetEntriesLastDaysRequest(7))
    );

    $mockClient->assertSent(GetTimesheetEntriesLastDaysRequest::class);
    expect($response->successful())->toBeTrue();
});

it('sends correct domain with date >= filter', function () {
    $mockClient = new MockClient([
        GetTimesheetEntriesLastDaysRequest::class => MockResponse::fixture('Api/Timesheets/timesheet-entries-last-days'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $connector->send(new GetTimesheetEntriesLastDaysRequest(7));

    $mockClient->assertSent(function (GetTimesheetEntriesLastDaysRequest $request) {
        $body = $request->body()->all();
        $domain = data_get($body, 'domain', []);

        return count($domain) === 1
            && data_get($domain, '0.0') === 'date'
            && data_get($domain, '0.1') === '>=';
    });
});

it('parses timesheet entries from response', function () {
    $mockClient = new MockClient([
        GetTimesheetEntriesLastDaysRequest::class => MockResponse::fixture('Api/Timesheets/timesheet-entries-last-days'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = TimesheetEntriesResponse::fromResponse(
        $connector->send(new GetTimesheetEntriesLastDaysRequest(7))
    );

    $entries = $response->entries();

    expect($entries)->toHaveCount(2);
    expect(data_get($entries, '0.id'))->toBe(6);
    expect(data_get($entries, '0.date'))->toBe('2026-06-09');
});
