<?php

use CodebarAg\Odoo\Dto\CallKw\Timesheets\CreateTimesheetDto;
use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\CallKw\Timesheets\CreateTimesheetRequest;
use CodebarAg\Odoo\Responses\CallKw\Timesheets\CreateTimesheetResponse;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('sends request to correct endpoint', function () {
    $mockClient = new MockClient([
        CreateTimesheetRequest::class => MockResponse::fixture('CallKw/Timesheets/create_timesheet'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'demo');
    $connector->withMockClient($mockClient);

    $response = CreateTimesheetResponse::fromResponse(
        $connector->send(new CreateTimesheetRequest(new CreateTimesheetDto('Test entry', 1, 10, '2024-03-15', 2.5)))
    );

    $mockClient->assertSent(CreateTimesheetRequest::class);
    expect($response->successful())->toBeTrue();
});

it('sends correct json-rpc body', function () {
    $mockClient = new MockClient([
        CreateTimesheetRequest::class => MockResponse::fixture('CallKw/Timesheets/create_timesheet'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'demo');
    $connector->withMockClient($mockClient);

    $connector->send(new CreateTimesheetRequest(new CreateTimesheetDto('Test entry', 1, 10, '2024-03-15', 2.5)));

    $mockClient->assertSent(function (CreateTimesheetRequest $request) {
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
        CreateTimesheetRequest::class => MockResponse::fixture('CallKw/Timesheets/create_timesheet'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'demo');
    $connector->withMockClient($mockClient);

    $response = CreateTimesheetResponse::fromResponse(
        $connector->send(new CreateTimesheetRequest(new CreateTimesheetDto('Test entry', 1, 10, '2024-03-15', 2.5)))
    );

    expect($response->id())->toBe(42);
});
