<?php

use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\Session\Auth\LogoutRequest;
use CodebarAg\Odoo\Responses\Session\LogoutResponse;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('sends request to correct endpoint', function () {
    $mockClient = new MockClient([
        LogoutRequest::class => MockResponse::fixture('Auth/logout'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'demo');
    $connector->withMockClient($mockClient);

    $response = LogoutResponse::fromResponse(
        $connector->send(new LogoutRequest())
    );

    $mockClient->assertSent(LogoutRequest::class);
    expect($response->successful())->toBeTrue();
});

it('sends correct json-rpc body', function () {
    $mockClient = new MockClient([
        LogoutRequest::class => MockResponse::fixture('Auth/logout'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'demo');
    $connector->withMockClient($mockClient);

    $connector->send(new LogoutRequest());

    $mockClient->assertSent(function (LogoutRequest $request) {
        $body = $request->body()->all();

        return $body['jsonrpc'] === '2.0'
            && $body['method'] === 'call'
            && isset($body['params']);
    });
});

it('parses response correctly', function () {
    $mockClient = new MockClient([
        LogoutRequest::class => MockResponse::fixture('Auth/logout'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'demo');
    $connector->withMockClient($mockClient);

    $response = LogoutResponse::fromResponse(
        $connector->send(new LogoutRequest())
    );

    expect($response->successful())->toBeTrue();
});
