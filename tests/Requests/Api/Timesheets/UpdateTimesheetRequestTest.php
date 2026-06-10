<?php

use CodebarAg\Odoo\Dto\Timesheets\UpdateTimesheetDto;
use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\Api\Timesheets\UpdateTimesheetRequest;
use CodebarAg\Odoo\Responses\Api\Timesheets\MutateTimesheetResponse;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('sends request to correct endpoint', function () {
    $mockClient = new MockClient([
        UpdateTimesheetRequest::class => MockResponse::fixture('Api/Timesheets/mutate-timesheet'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $dto = new UpdateTimesheetDto(id: 6, values: ['unit_amount' => 2.0]);

    $response = MutateTimesheetResponse::fromResponse(
        $connector->send(new UpdateTimesheetRequest($dto))
    );

    $mockClient->assertSent(UpdateTimesheetRequest::class);
    expect($response->successful())->toBeTrue();
});

it('sends correct ids and vals body', function () {
    $mockClient = new MockClient([
        UpdateTimesheetRequest::class => MockResponse::fixture('Api/Timesheets/mutate-timesheet'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $dto = new UpdateTimesheetDto(id: 6, values: ['unit_amount' => 2.0, 'name' => 'Updated']);

    $connector->send(new UpdateTimesheetRequest($dto));

    $mockClient->assertSent(function (UpdateTimesheetRequest $request) {
        $body = $request->body()->all();

        return $body['ids'] === [6]
            && $body['vals']['unit_amount'] === 2.0
            && $body['vals']['name'] === 'Updated';
    });
});

it('confirms successful update', function () {
    $mockClient = new MockClient([
        UpdateTimesheetRequest::class => MockResponse::fixture('Api/Timesheets/mutate-timesheet'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $dto = new UpdateTimesheetDto(id: 6, values: ['unit_amount' => 2.0]);

    $response = MutateTimesheetResponse::fromResponse(
        $connector->send(new UpdateTimesheetRequest($dto))
    );

    expect($response->ok())->toBeTrue();
});
