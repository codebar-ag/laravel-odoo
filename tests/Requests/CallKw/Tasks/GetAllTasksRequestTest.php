<?php

use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\CallKw\Tasks\GetAllTasksRequest;
use CodebarAg\Odoo\Responses\CallKw\Tasks\TasksResponse;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('sends request to correct endpoint', function () {
    $mockClient = new MockClient([
        GetAllTasksRequest::class => MockResponse::fixture('CallKw/Tasks/get_all_tasks'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'demo');
    $connector->withMockClient($mockClient);

    $response = TasksResponse::fromResponse(
        $connector->send(new GetAllTasksRequest)
    );

    $mockClient->assertSent(GetAllTasksRequest::class);
    expect($response->successful())->toBeTrue();
});

it('sends correct json-rpc body', function () {
    $mockClient = new MockClient([
        GetAllTasksRequest::class => MockResponse::fixture('CallKw/Tasks/get_all_tasks'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'demo');
    $connector->withMockClient($mockClient);

    $connector->send(new GetAllTasksRequest);

    $mockClient->assertSent(function (GetAllTasksRequest $request) {
        $body = $request->body()->all();

        return $body['jsonrpc'] === '2.0'
            && $body['method'] === 'call'
            && $body['params']['model'] === 'project.task'
            && isset($body['params']['args'])
            && isset($body['params']['kwargs']);
    });
});

it('parses response correctly', function () {
    $mockClient = new MockClient([
        GetAllTasksRequest::class => MockResponse::fixture('CallKw/Tasks/get_all_tasks'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'demo');
    $connector->withMockClient($mockClient);

    $response = TasksResponse::fromResponse(
        $connector->send(new GetAllTasksRequest)
    );

    $tasks = $response->tasks();

    expect($tasks)->toHaveCount(1);
    expect($tasks[0]->id)->toBe(10);
    expect($tasks[0]->name)->toBe('Fix Bug');
});
