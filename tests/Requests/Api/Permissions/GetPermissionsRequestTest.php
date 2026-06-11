<?php

use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\Api\Permissions\GetPermissionsRequest;
use CodebarAg\Odoo\Responses\Api\Permissions\PermissionsResponse;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('sends request to correct endpoint', function () {
    $mockClient = new MockClient([
        GetPermissionsRequest::class => MockResponse::fixture('Api/Permissions/permissions-allowed'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = PermissionsResponse::fromResponse(
        $connector->send(new GetPermissionsRequest('account.analytic.line', 'read'))
    );

    $mockClient->assertSent(GetPermissionsRequest::class);
    expect($response->successful())->toBeTrue();
});

it('includes model name in endpoint and sends operation in body', function () {
    $mockClient = new MockClient([
        GetPermissionsRequest::class => MockResponse::fixture('Api/Permissions/permissions-allowed'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $connector->send(new GetPermissionsRequest('account.analytic.line', 'write'));

    $mockClient->assertSent(function (GetPermissionsRequest $request) {
        $body = $request->body()->all();

        return data_get($body, 'operation') === 'write'
            && str_contains($request->resolveEndpoint(), 'account.analytic.line');
    });
});

it('returns true when permission is allowed', function () {
    $mockClient = new MockClient([
        GetPermissionsRequest::class => MockResponse::fixture('Api/Permissions/permissions-allowed'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = PermissionsResponse::fromResponse(
        $connector->send(new GetPermissionsRequest('account.analytic.line', 'read'))
    );

    expect($response->allowed())->toBeTrue();
});

it('returns false when permission is denied', function () {
    $mockClient = new MockClient([
        GetPermissionsRequest::class => MockResponse::fixture('Api/Permissions/permissions-denied'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = PermissionsResponse::fromResponse(
        $connector->send(new GetPermissionsRequest('account.analytic.line', 'unlink'))
    );

    expect($response->allowed())->toBeFalse();
});
