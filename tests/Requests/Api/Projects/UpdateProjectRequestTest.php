<?php

use CodebarAg\Odoo\Dto\Projects\UpdateProjectDto;
use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\Api\Projects\UpdateProjectRequest;
use CodebarAg\Odoo\Responses\Api\Projects\MutateProjectResponse;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('sends request to correct endpoint', function () {
    $mockClient = new MockClient([
        UpdateProjectRequest::class => MockResponse::fixture('Api/Projects/mutate-project'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $dto = new UpdateProjectDto(id: 42, name: 'Updated Name');

    $response = MutateProjectResponse::fromResponse(
        $connector->send(new UpdateProjectRequest($dto))
    );

    $mockClient->assertSent(UpdateProjectRequest::class);
    expect($response->successful())->toBeTrue();
});

it('sends correct ids and vals body', function () {
    $mockClient = new MockClient([
        UpdateProjectRequest::class => MockResponse::fixture('Api/Projects/mutate-project'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $dto = new UpdateProjectDto(id: 42, name: 'Updated Name', allocatedHours: 12.5);

    $connector->send(new UpdateProjectRequest($dto));

    $mockClient->assertSent(function (UpdateProjectRequest $request) {
        $body = $request->body()->all();

        return data_get($body, 'ids') === [42]
            && data_get($body, 'vals.name') === 'Updated Name'
            && data_get($body, 'vals.allocated_hours') === 12.5;
    });
});

it('confirms successful update', function () {
    $mockClient = new MockClient([
        UpdateProjectRequest::class => MockResponse::fixture('Api/Projects/mutate-project'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $dto = new UpdateProjectDto(id: 42, name: 'Updated Name');

    $response = MutateProjectResponse::fromResponse(
        $connector->send(new UpdateProjectRequest($dto))
    );

    expect($response->ok())->toBeTrue();
});
