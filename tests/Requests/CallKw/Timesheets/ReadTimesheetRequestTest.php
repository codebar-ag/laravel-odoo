<?php

use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\CallKw\Timesheets\ReadTimesheetRequest;
use CodebarAg\Odoo\Responses\CallKw\Timesheets\TimesheetResponse;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('sends request to correct endpoint', function () {
    $mockClient = new MockClient([
        ReadTimesheetRequest::class => MockResponse::fixture('CallKw/Timesheets/read_timesheet'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'demo');
    $connector->withMockClient($mockClient);

    $response = TimesheetResponse::fromResponse(
        $connector->send(new ReadTimesheetRequest(42))
    );

    $mockClient->assertSent(ReadTimesheetRequest::class);
    expect($response->successful())->toBeTrue();
});

it('sends correct json-rpc body', function () {
    $mockClient = new MockClient([
        ReadTimesheetRequest::class => MockResponse::fixture('CallKw/Timesheets/read_timesheet'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'demo');
    $connector->withMockClient($mockClient);

    $connector->send(new ReadTimesheetRequest(42));

    $mockClient->assertSent(function (ReadTimesheetRequest $request) {
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
        ReadTimesheetRequest::class => MockResponse::fixture('CallKw/Timesheets/read_timesheet'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'demo');
    $connector->withMockClient($mockClient);

    $response = TimesheetResponse::fromResponse(
        $connector->send(new ReadTimesheetRequest(42))
    );

    $dto = $response->dto();

    expect($dto)->not->toBeNull();
    expect($dto->id)->toBe(42);
    expect($dto->name)->toBe('Working on feature');
    expect($dto->unitAmount)->toBe(2.5);
});
