<?php

use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\Api\Tasks\GetAllTasksRequest;
use CodebarAg\Odoo\Responses\Api\Tasks\TasksResponse;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('sends request to correct endpoint', function () {
    $mockClient = new MockClient([
        GetAllTasksRequest::class => MockResponse::fixture('Api/Tasks/tasks'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = TasksResponse::fromResponse(
        $connector->send(new GetAllTasksRequest())
    );

    $mockClient->assertSent(GetAllTasksRequest::class);
    expect($response->successful())->toBeTrue();
});

it('sends correct body with domain and limit', function () {
    $mockClient = new MockClient([
        GetAllTasksRequest::class => MockResponse::fixture('Api/Tasks/tasks'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $connector->send(new GetAllTasksRequest());

    $mockClient->assertSent(function (GetAllTasksRequest $request) {
        $body = $request->body()->all();

        return $body['domain'] === []
            && $body['limit'] === 100
            && in_array('name', $body['fields']);
    });
});

it('parses tasks from response', function () {
    $mockClient = new MockClient([
        GetAllTasksRequest::class => MockResponse::fixture('Api/Tasks/tasks'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = TasksResponse::fromResponse(
        $connector->send(new GetAllTasksRequest())
    );

    $tasks = $response->tasks();

    expect($tasks)->toHaveCount(1);
    expect($tasks[0]->id)->toBe(1);
    expect($tasks[0]->name)->toBe('Fix Bug');
    expect($tasks[0]->projectId)->toBe(1);
    expect($tasks[0]->projectName)->toBe('Internal');
});
