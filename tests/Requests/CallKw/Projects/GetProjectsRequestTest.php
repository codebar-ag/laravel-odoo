<?php

use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\CallKw\Projects\GetProjectsRequest;
use CodebarAg\Odoo\Responses\CallKw\Projects\ProjectsResponse;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('sends request to correct endpoint', function () {
    $mockClient = new MockClient([
        GetProjectsRequest::class => MockResponse::fixture('CallKw/Projects/get_projects'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'demo');
    $connector->withMockClient($mockClient);

    $response = ProjectsResponse::fromResponse(
        $connector->send(new GetProjectsRequest())
    );

    $mockClient->assertSent(GetProjectsRequest::class);
    expect($response->successful())->toBeTrue();
});

it('sends correct json-rpc body', function () {
    $mockClient = new MockClient([
        GetProjectsRequest::class => MockResponse::fixture('CallKw/Projects/get_projects'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'demo');
    $connector->withMockClient($mockClient);

    $connector->send(new GetProjectsRequest());

    $mockClient->assertSent(function (GetProjectsRequest $request) {
        $body = $request->body()->all();

        return $body['jsonrpc'] === '2.0'
            && $body['method'] === 'call'
            && $body['params']['model'] === 'project.project'
            && isset($body['params']['args'])
            && isset($body['params']['kwargs']);
    });
});

it('parses response correctly', function () {
    $mockClient = new MockClient([
        GetProjectsRequest::class => MockResponse::fixture('CallKw/Projects/get_projects'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'demo');
    $connector->withMockClient($mockClient);

    $response = ProjectsResponse::fromResponse(
        $connector->send(new GetProjectsRequest())
    );

    $projects = $response->projects();

    expect($projects)->toHaveCount(1);
    expect($projects[0]->id)->toBe(1);
    expect($projects[0]->name)->toBe('My Project');
});
