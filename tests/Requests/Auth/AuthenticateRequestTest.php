<?php

use CodebarAg\Odoo\Dto\Auth\AuthenticateDto;
use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\Auth\AuthenticateRequest;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('sends authenticate request to correct endpoint', function () {
    $mockClient = new MockClient([
        AuthenticateRequest::class => MockResponse::fixture('Auth/authenticate'),
    ]);

    $connector = new OdooConnector('https://demo.odoo.com', 'demo');
    $connector->withMockClient($mockClient);

    $response = $connector->login(new AuthenticateDto('demo', 'admin', 'secret'));

    $mockClient->assertSent(AuthenticateRequest::class);
    expect($response->successful())->toBeTrue();
});

it('returns auth dto with uid and session id', function () {
    $mockClient = new MockClient([
        AuthenticateRequest::class => MockResponse::fixture('Auth/authenticate'),
    ]);

    $connector = new OdooConnector('https://demo.odoo.com', 'demo');
    $connector->withMockClient($mockClient);

    $response = $connector->login(new AuthenticateDto('demo', 'admin', 'secret'));
    $dto = $response->dto();

    expect($dto)->not->toBeNull()
        ->and($dto->uid)->toBe(1)
        ->and($dto->sessionId)->toBe('abc123sessiontoken')
        ->and($dto->db)->toBe('mycompany')
        ->and($dto->login)->toBe('admin')
        ->and($dto->totpRequired)->toBeFalse();
});

it('sends correct json-rpc body', function () {
    $mockClient = new MockClient([
        AuthenticateRequest::class => MockResponse::fixture('Auth/authenticate'),
    ]);

    $connector = new OdooConnector('https://demo.odoo.com', 'demo');
    $connector->withMockClient($mockClient);

    $connector->login(new AuthenticateDto('demo', 'admin', 'secret'));

    $mockClient->assertSent(function (AuthenticateRequest $request) {
        $body = $request->body()->all();

        return $body['jsonrpc'] === '2.0'
            && $body['method'] === 'call'
            && $body['params']['db'] === 'demo'
            && $body['params']['login'] === 'admin'
            && $body['params']['password'] === 'secret';
    });
});
