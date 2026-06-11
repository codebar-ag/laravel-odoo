<?php

use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\Api\Projects\GetProjectsRequest;
use CodebarAg\Odoo\Responses\Api\Projects\ProjectsResponse;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('sends request to correct endpoint', function () {
    $mockClient = new MockClient([
        GetProjectsRequest::class => MockResponse::fixture('Api/Projects/projects'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = ProjectsResponse::fromResponse(
        $connector->send(new GetProjectsRequest)
    );

    $mockClient->assertSent(GetProjectsRequest::class);
    expect($response->successful())->toBeTrue();
});

it('sends correct body with domain and limit', function () {
    $mockClient = new MockClient([
        GetProjectsRequest::class => MockResponse::fixture('Api/Projects/projects'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $connector->send(new GetProjectsRequest);

    $mockClient->assertSent(function (GetProjectsRequest $request) {
        $body = $request->body()->all();

        return data_get($body, 'domain') === []
            && data_get($body, 'limit') === 100
            && in_array('name', data_get($body, 'fields', []));
    });
});

it('parses projects from response', function () {
    $mockClient = new MockClient([
        GetProjectsRequest::class => MockResponse::fixture('Api/Projects/projects'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = ProjectsResponse::fromResponse(
        $connector->send(new GetProjectsRequest)
    );

    $projects = $response->projects();

    expect($projects)->toHaveCount(1);
    expect(data_get($projects, '0.id'))->toBe(1);
    expect(data_get($projects, '0.name'))->toBe('Internal');
    expect(data_get($projects, '0.userId'))->toBe(1);
    expect(data_get($projects, '0.userName'))->toBe('OdooBot');
    expect(data_get($projects, '0.active'))->toBeTrue();
    expect(data_get($projects, '0.companyName'))->toBe('MyCompany');
    expect(data_get($projects, '0.effectiveHours'))->toBe(87.25);
    expect(data_get($projects, '0.taskCount'))->toBe(2);
    expect(data_get($projects, '0.privacyVisibility'))->toBe('portal');
});
