<?php

use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\Session\Health\HealthRequest;
use CodebarAg\Odoo\Responses\Session\HealthResponse;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('sends request to correct endpoint', function () {
    $mockClient = new MockClient([
        HealthRequest::class => MockResponse::fixture('Session/health'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'demo');
    $connector->withMockClient($mockClient);

    $response = HealthResponse::fromResponse(
        $connector->send(new HealthRequest)
    );

    $mockClient->assertSent(HealthRequest::class);
    expect($response->successful())->toBeTrue();
});

it('parses response correctly', function () {
    $mockClient = new MockClient([
        HealthRequest::class => MockResponse::fixture('Session/health'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'demo');
    $connector->withMockClient($mockClient);

    $response = HealthResponse::fromResponse(
        $connector->send(new HealthRequest)
    );

    expect($response->isHealthy())->toBeTrue();
});
