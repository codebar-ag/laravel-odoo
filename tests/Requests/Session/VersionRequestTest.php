<?php

use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\Session\Version\GetOdooVersionRequest;
use CodebarAg\Odoo\Responses\Session\VersionResponse;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('sends request to correct endpoint', function () {
    $mockClient = new MockClient([
        GetOdooVersionRequest::class => MockResponse::fixture('Session/version'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = VersionResponse::fromResponse(
        $connector->send(new GetOdooVersionRequest())
    );

    $mockClient->assertSent(GetOdooVersionRequest::class);
    expect($response->successful())->toBeTrue();
});

it('parses response correctly', function () {
    $mockClient = new MockClient([
        GetOdooVersionRequest::class => MockResponse::fixture('Session/version'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = VersionResponse::fromResponse(
        $connector->send(new GetOdooVersionRequest())
    );

    expect($response->serverVersion())->toBe('saas~19');
    expect($response->serie())->toBe('saas~19.3+e');
});
