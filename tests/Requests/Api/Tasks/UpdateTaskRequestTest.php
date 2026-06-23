<?php

use CodebarAg\Odoo\Dto\Tasks\UpdateTaskDto;
use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\Api\Tasks\UpdateTaskRequest;
use CodebarAg\Odoo\Responses\Api\Tasks\MutateTaskResponse;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('sends request to correct endpoint', function () {
    $mockClient = new MockClient([
        UpdateTaskRequest::class => MockResponse::fixture('Api/Tasks/mutate-task'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $dto = new UpdateTaskDto(id: 42, name: 'Updated Name');

    $response = MutateTaskResponse::fromResponse(
        $connector->send(new UpdateTaskRequest($dto))
    );

    $mockClient->assertSent(UpdateTaskRequest::class);
    expect($response->successful())->toBeTrue();
});

it('sends correct ids and vals body', function () {
    $mockClient = new MockClient([
        UpdateTaskRequest::class => MockResponse::fixture('Api/Tasks/mutate-task'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $dto = new UpdateTaskDto(id: 42, name: 'Updated Name', stageId: 2);

    $connector->send(new UpdateTaskRequest($dto));

    $mockClient->assertSent(function (UpdateTaskRequest $request) {
        $body = $request->body()->all();

        return data_get($body, 'ids') === [42]
            && data_get($body, 'vals.name') === 'Updated Name'
            && data_get($body, 'vals.stage_id') === 2;
    });
});

it('confirms successful update', function () {
    $mockClient = new MockClient([
        UpdateTaskRequest::class => MockResponse::fixture('Api/Tasks/mutate-task'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $dto = new UpdateTaskDto(id: 42, name: 'Updated Name');

    $response = MutateTaskResponse::fromResponse(
        $connector->send(new UpdateTaskRequest($dto))
    );

    expect($response->ok())->toBeTrue();
});
