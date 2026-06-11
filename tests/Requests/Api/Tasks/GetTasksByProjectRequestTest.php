<?php

use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\Api\Tasks\GetTasksByProjectRequest;
use CodebarAg\Odoo\Responses\Api\Tasks\TasksResponse;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('sends request to correct endpoint', function () {
    $mockClient = new MockClient([
        GetTasksByProjectRequest::class => MockResponse::fixture('Api/Tasks/tasks-by-project'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = TasksResponse::fromResponse(
        $connector->send(new GetTasksByProjectRequest(1))
    );

    $mockClient->assertSent(GetTasksByProjectRequest::class);
    expect($response->successful())->toBeTrue();
});

it('sends correct domain filtering by project id', function () {
    $mockClient = new MockClient([
        GetTasksByProjectRequest::class => MockResponse::fixture('Api/Tasks/tasks-by-project'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $connector->send(new GetTasksByProjectRequest(5));

    $mockClient->assertSent(function (GetTasksByProjectRequest $request) {
        $body = $request->body()->all();

        return data_get($body, 'domain') === [['project_id', '=', 5]]
            && data_get($body, 'limit') === 100;
    });
});

it('parses tasks from response', function () {
    $mockClient = new MockClient([
        GetTasksByProjectRequest::class => MockResponse::fixture('Api/Tasks/tasks-by-project'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = TasksResponse::fromResponse(
        $connector->send(new GetTasksByProjectRequest(1))
    );

    $tasks = $response->tasks();

    expect($tasks)->toHaveCount(1);
    expect(data_get($tasks, '0.id'))->toBe(2);
    expect(data_get($tasks, '0.name'))->toBe('Meeting');
    expect(data_get($tasks, '0.projectId'))->toBe(1);
    expect(data_get($tasks, '0.projectName'))->toBe('Internal');
});
