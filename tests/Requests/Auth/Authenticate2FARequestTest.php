<?php

use CodebarAg\Odoo\Dto\Auth\Authenticate2FADto;
use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\Auth\Authenticate2FARequest;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('sends 2fa request to correct endpoint', function () {
    $mockClient = new MockClient([
        Authenticate2FARequest::class => MockResponse::fixture('Auth/authenticate_totp'),
    ]);

    $connector = new OdooConnector('https://demo.odoo.com', 'demo');
    $connector->withMockClient($mockClient);

    $response = $connector->verifyTotp(new Authenticate2FADto('123456'));

    $mockClient->assertSent(Authenticate2FARequest::class);
    expect($response->successful())->toBeTrue();
});

it('sends correct json-rpc body with totp_token', function () {
    $mockClient = new MockClient([
        Authenticate2FARequest::class => MockResponse::fixture('Auth/authenticate_totp'),
    ]);

    $connector = new OdooConnector('https://demo.odoo.com', 'demo');
    $connector->withMockClient($mockClient);

    $connector->verifyTotp(new Authenticate2FADto('123456'));

    $mockClient->assertSent(function (Authenticate2FARequest $request) {
        $body = $request->body()->all();

        return $body['jsonrpc'] === '2.0'
            && $body['method'] === 'call'
            && $body['params']['totp_token'] === '123456';
    });
});

it('returns auth dto on successful 2fa', function () {
    $mockClient = new MockClient([
        Authenticate2FARequest::class => MockResponse::fixture('Auth/authenticate_totp'),
    ]);

    $connector = new OdooConnector('https://demo.odoo.com', 'demo');
    $connector->withMockClient($mockClient);

    $response = $connector->verifyTotp(new Authenticate2FADto('123456'));
    $dto = $response->dto();

    expect($dto)->not->toBeNull()
        ->and($dto->uid)->toBe(1)
        ->and($dto->sessionId)->toBe('abc123sessiontoken');
});
