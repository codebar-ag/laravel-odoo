<?php

use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\Session\Database\GetDatabasesRequest;
use CodebarAg\Odoo\Responses\Session\DatabasesResponse;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('sends request to correct endpoint', function () {
    $mockClient = new MockClient([
        GetDatabasesRequest::class => MockResponse::fixture('Session/databases'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'demo');
    $connector->withMockClient($mockClient);

    $response = DatabasesResponse::fromResponse(
        $connector->send(new GetDatabasesRequest)
    );

    $mockClient->assertSent(GetDatabasesRequest::class);
    expect($response->successful())->toBeTrue();
});

it('parses response correctly', function () {
    $mockClient = new MockClient([
        GetDatabasesRequest::class => MockResponse::fixture('Session/databases'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'demo');
    $connector->withMockClient($mockClient);

    $response = DatabasesResponse::fromResponse(
        $connector->send(new GetDatabasesRequest)
    );

    expect($response->databases())->toContain('mycompany');
    expect($response->databases())->toContain('demo');
});
