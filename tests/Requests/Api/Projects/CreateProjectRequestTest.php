<?php

use CodebarAg\Odoo\Dto\Projects\CreateProjectDto;
use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\Api\Projects\CreateProjectRequest;
use CodebarAg\Odoo\Responses\Api\Projects\CreateProjectResponse;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('sends request to correct endpoint', function () {
    $mockClient = new MockClient([
        CreateProjectRequest::class => MockResponse::fixture('Api/Projects/create-project'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $dto = new CreateProjectDto(name: 'Test Project');

    $response = CreateProjectResponse::fromResponse(
        $connector->send(new CreateProjectRequest($dto))
    );

    $mockClient->assertSent(CreateProjectRequest::class);
    expect($response->successful())->toBeTrue();
});

it('sends correct vals_list body', function () {
    $mockClient = new MockClient([
        CreateProjectRequest::class => MockResponse::fixture('Api/Projects/create-project'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $dto = new CreateProjectDto(
        name: 'Test Project',
        description: 'A project',
        partnerId: 7,
        allocatedHours: 40.0,
        tagIds: [1, 2],
    );

    $connector->send(new CreateProjectRequest($dto));

    $mockClient->assertSent(function (CreateProjectRequest $request) {
        $body = $request->body()->all();

        return data_get($body, 'vals_list.name') === 'Test Project'
            && data_get($body, 'vals_list.description') === 'A project'
            && data_get($body, 'vals_list.partner_id') === 7
            && data_get($body, 'vals_list.allocated_hours') === 40.0
            && data_get($body, 'vals_list.tag_ids') === [[6, 0, [1, 2]]];
    });
});

it('returns the created project id', function () {
    $mockClient = new MockClient([
        CreateProjectRequest::class => MockResponse::fixture('Api/Projects/create-project'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $dto = new CreateProjectDto(name: 'Test Project');

    $response = CreateProjectResponse::fromResponse(
        $connector->send(new CreateProjectRequest($dto))
    );

    expect($response->id())->toBe(42);
});
