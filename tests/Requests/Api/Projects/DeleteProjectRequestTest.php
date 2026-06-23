<?php

use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\Api\Projects\DeleteProjectRequest;
use CodebarAg\Odoo\Responses\Api\Projects\MutateProjectResponse;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('sends request to correct endpoint', function () {
    $mockClient = new MockClient([
        DeleteProjectRequest::class => MockResponse::fixture('Api/Projects/delete-project'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = MutateProjectResponse::fromResponse(
        $connector->send(new DeleteProjectRequest(42))
    );

    $mockClient->assertSent(DeleteProjectRequest::class);
    expect($response->successful())->toBeTrue();
});

it('sends correct ids body', function () {
    $mockClient = new MockClient([
        DeleteProjectRequest::class => MockResponse::fixture('Api/Projects/delete-project'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $connector->send(new DeleteProjectRequest(42));

    $mockClient->assertSent(function (DeleteProjectRequest $request) {
        $body = $request->body()->all();

        return data_get($body, 'ids') === [42];
    });
});

it('confirms successful deletion', function () {
    $mockClient = new MockClient([
        DeleteProjectRequest::class => MockResponse::fixture('Api/Projects/delete-project'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = MutateProjectResponse::fromResponse(
        $connector->send(new DeleteProjectRequest(42))
    );

    expect($response->ok())->toBeTrue();
});
