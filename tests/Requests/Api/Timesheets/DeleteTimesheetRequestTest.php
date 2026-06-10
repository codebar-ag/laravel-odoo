<?php

use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\Api\Timesheets\DeleteTimesheetRequest;
use CodebarAg\Odoo\Responses\Api\Timesheets\MutateTimesheetResponse;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('sends request to correct endpoint', function () {
    $mockClient = new MockClient([
        DeleteTimesheetRequest::class => MockResponse::fixture('Api/Timesheets/delete-timesheet'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = MutateTimesheetResponse::fromResponse(
        $connector->send(new DeleteTimesheetRequest(6))
    );

    $mockClient->assertSent(DeleteTimesheetRequest::class);
    expect($response->successful())->toBeTrue();
});

it('sends correct ids body', function () {
    $mockClient = new MockClient([
        DeleteTimesheetRequest::class => MockResponse::fixture('Api/Timesheets/delete-timesheet'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $connector->send(new DeleteTimesheetRequest(42));

    $mockClient->assertSent(function (DeleteTimesheetRequest $request) {
        $body = $request->body()->all();

        return $body['ids'] === [42];
    });
});

it('confirms successful deletion', function () {
    $mockClient = new MockClient([
        DeleteTimesheetRequest::class => MockResponse::fixture('Api/Timesheets/delete-timesheet'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = MutateTimesheetResponse::fromResponse(
        $connector->send(new DeleteTimesheetRequest(6))
    );

    expect($response->ok())->toBeTrue();
});
