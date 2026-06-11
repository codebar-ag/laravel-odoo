<?php

use CodebarAg\Odoo\Requests\Session\Version\GetOdooVersionRequest;
use CodebarAg\Odoo\Responses\Session\VersionResponse;

it('sends request to correct endpoint', function () {
    [$connector, $mockClient] = odooMockClient([
        GetOdooVersionRequest::class => 'Session/version',
    ]);

    $response = VersionResponse::fromResponse(
        $connector->send(new GetOdooVersionRequest)
    );

    $mockClient->assertSent(GetOdooVersionRequest::class);
    expect($response->successful())->toBeTrue();
});

it('parses response correctly', function () {
    [$connector] = odooMockClient([
        GetOdooVersionRequest::class => 'Session/version',
    ]);

    $response = VersionResponse::fromResponse(
        $connector->send(new GetOdooVersionRequest)
    );

    expect($response->serverVersion())->toBe('saas~19');
    expect($response->serie())->toBe('saas~19.3+e');
});
