<?php

use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\CallKw\Timesheets\DeleteTimesheetRequest;
use CodebarAg\Odoo\Responses\CallKw\Timesheets\MutateTimesheetResponse;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('sends request to correct endpoint', function () {
    $mockClient = new MockClient([
        DeleteTimesheetRequest::class => MockResponse::fixture('CallKw/Timesheets/delete_timesheet'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'demo');
    $connector->withMockClient($mockClient);

    $response = MutateTimesheetResponse::fromResponse(
        $connector->send(new DeleteTimesheetRequest(42))
    );

    $mockClient->assertSent(DeleteTimesheetRequest::class);
    expect($response->successful())->toBeTrue();
});

it('sends correct json-rpc body', function () {
    $mockClient = new MockClient([
        DeleteTimesheetRequest::class => MockResponse::fixture('CallKw/Timesheets/delete_timesheet'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'demo');
    $connector->withMockClient($mockClient);

    $connector->send(new DeleteTimesheetRequest(42));

    $mockClient->assertSent(function (DeleteTimesheetRequest $request) {
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
        DeleteTimesheetRequest::class => MockResponse::fixture('CallKw/Timesheets/delete_timesheet'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'demo');
    $connector->withMockClient($mockClient);

    $response = MutateTimesheetResponse::fromResponse(
        $connector->send(new DeleteTimesheetRequest(42))
    );

    expect($response->ok())->toBeTrue();
});
