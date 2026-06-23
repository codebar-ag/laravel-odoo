<?php

use CodebarAg\Odoo\Dto\Tasks\CreateTaskDto;
use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\Api\Tasks\CreateTaskRequest;
use CodebarAg\Odoo\Responses\Api\Tasks\CreateTaskResponse;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('sends request to correct endpoint', function () {
    $mockClient = new MockClient([
        CreateTaskRequest::class => MockResponse::fixture('Api/Tasks/create-task'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $dto = new CreateTaskDto(name: 'Test Task');

    $response = CreateTaskResponse::fromResponse(
        $connector->send(new CreateTaskRequest($dto))
    );

    $mockClient->assertSent(CreateTaskRequest::class);
    expect($response->successful())->toBeTrue();
});

it('sends correct vals_list body', function () {
    $mockClient = new MockClient([
        CreateTaskRequest::class => MockResponse::fixture('Api/Tasks/create-task'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $dto = new CreateTaskDto(
        name: 'Test Task',
        projectId: 3,
        priority: '1',
        userIds: [5, 6],
    );

    $connector->send(new CreateTaskRequest($dto));

    $mockClient->assertSent(function (CreateTaskRequest $request) {
        $body = $request->body()->all();

        return data_get($body, 'vals_list.name') === 'Test Task'
            && data_get($body, 'vals_list.project_id') === 3
            && data_get($body, 'vals_list.priority') === '1'
            && data_get($body, 'vals_list.user_ids') === [[6, 0, [5, 6]]];
    });
});

it('returns the created task id', function () {
    $mockClient = new MockClient([
        CreateTaskRequest::class => MockResponse::fixture('Api/Tasks/create-task'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $dto = new CreateTaskDto(name: 'Test Task');

    $response = CreateTaskResponse::fromResponse(
        $connector->send(new CreateTaskRequest($dto))
    );

    expect($response->id())->toBe(42);
});
