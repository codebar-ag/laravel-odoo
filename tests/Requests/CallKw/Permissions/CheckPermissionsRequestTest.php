<?php

use CodebarAg\Odoo\Dto\Session\Permissions\PermissionDto;
use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\Session\Auth\BasicAuth\GetPermissionsRequest;
use CodebarAg\Odoo\Responses\CallKw\Permissions\PermissionsResponse;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('sends request to correct endpoint', function () {
    $mockClient = new MockClient([
        GetPermissionsRequest::class => MockResponse::fixture('CallKw/Permissions/check_permissions'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'demo');
    $connector->withMockClient($mockClient);

    $response = PermissionsResponse::fromResponse(
        $connector->send(new GetPermissionsRequest(new PermissionDto('res.partner', 'read')))
    );

    $mockClient->assertSent(GetPermissionsRequest::class);
    expect($response->successful())->toBeTrue();
});

it('sends correct json-rpc body', function () {
    $mockClient = new MockClient([
        GetPermissionsRequest::class => MockResponse::fixture('CallKw/Permissions/check_permissions'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'demo');
    $connector->withMockClient($mockClient);

    $connector->send(new GetPermissionsRequest(new PermissionDto('res.partner', 'read')));

    $mockClient->assertSent(function (GetPermissionsRequest $request) {
        $body = $request->body()->all();

        return $body['jsonrpc'] === '2.0'
            && $body['method'] === 'call'
            && isset($body['params']['model'])
            && isset($body['params']['args'])
            && isset($body['params']['kwargs']);
    });
});

it('parses response correctly', function () {
    $mockClient = new MockClient([
        GetPermissionsRequest::class => MockResponse::fixture('CallKw/Permissions/check_permissions'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'demo');
    $connector->withMockClient($mockClient);

    $response = PermissionsResponse::fromResponse(
        $connector->send(new GetPermissionsRequest(new PermissionDto('res.partner', 'read')))
    );

    expect($response->allowed())->toBeTrue();
});
