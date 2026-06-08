<?php

use CodebarAg\Odoo\Dto\CallKw\Timesheets\UpdateTimesheetDto;
use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\CallKw\Timesheets\UpdateTimesheetRequest;
use CodebarAg\Odoo\Responses\CallKw\Timesheets\MutateTimesheetResponse;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('sends request to correct endpoint', function () {
    $mockClient = new MockClient([
        UpdateTimesheetRequest::class => MockResponse::fixture('CallKw/Timesheets/update_timesheet'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'demo');
    $connector->withMockClient($mockClient);

    $response = MutateTimesheetResponse::fromResponse(
        $connector->send(new UpdateTimesheetRequest(new UpdateTimesheetDto(42, ['unit_amount' => 3.0])))
    );

    $mockClient->assertSent(UpdateTimesheetRequest::class);
    expect($response->successful())->toBeTrue();
});

it('sends correct json-rpc body', function () {
    $mockClient = new MockClient([
        UpdateTimesheetRequest::class => MockResponse::fixture('CallKw/Timesheets/update_timesheet'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'demo');
    $connector->withMockClient($mockClient);

    $connector->send(new UpdateTimesheetRequest(new UpdateTimesheetDto(42, ['unit_amount' => 3.0])));

    $mockClient->assertSent(function (UpdateTimesheetRequest $request) {
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
        UpdateTimesheetRequest::class => MockResponse::fixture('CallKw/Timesheets/update_timesheet'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'demo');
    $connector->withMockClient($mockClient);

    $response = MutateTimesheetResponse::fromResponse(
        $connector->send(new UpdateTimesheetRequest(new UpdateTimesheetDto(42, ['unit_amount' => 3.0])))
    );

    expect($response->ok())->toBeTrue();
});
