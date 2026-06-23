<?php

use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\Api\Tasks\DeleteTaskRequest;
use CodebarAg\Odoo\Responses\Api\Tasks\MutateTaskResponse;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('sends request to correct endpoint', function () {
    $mockClient = new MockClient([
        DeleteTaskRequest::class => MockResponse::fixture('Api/Tasks/delete-task'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = MutateTaskResponse::fromResponse(
        $connector->send(new DeleteTaskRequest(42))
    );

    $mockClient->assertSent(DeleteTaskRequest::class);
    expect($response->successful())->toBeTrue();
});

it('sends correct ids body', function () {
    $mockClient = new MockClient([
        DeleteTaskRequest::class => MockResponse::fixture('Api/Tasks/delete-task'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $connector->send(new DeleteTaskRequest(42));

    $mockClient->assertSent(function (DeleteTaskRequest $request) {
        $body = $request->body()->all();

        return data_get($body, 'ids') === [42];
    });
});

it('confirms successful deletion', function () {
    $mockClient = new MockClient([
        DeleteTaskRequest::class => MockResponse::fixture('Api/Tasks/delete-task'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = MutateTaskResponse::fromResponse(
        $connector->send(new DeleteTaskRequest(42))
    );

    expect($response->ok())->toBeTrue();
});
