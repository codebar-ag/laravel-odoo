<?php

use CodebarAg\Odoo\Dto\Auth\AuthenticatePasskeyDto;
use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\Auth\AuthenticatePasskeyRequest;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('sends authenticate passkey request to correct endpoint', function () {
    $mockClient = new MockClient([
        AuthenticatePasskeyRequest::class => MockResponse::fixture('Auth/authenticate_passkey'),
    ]);

    $connector = new OdooConnector('https://demo.odoo.com', 'demo');
    $connector->withMockClient($mockClient);

    $dto = new AuthenticatePasskeyDto(
        id: 'credentialId123',
        rawId: 'rawCredentialId123',
        type: 'public-key',
        response: [
            'clientDataJSON' => 'base64encodedClientData',
            'authenticatorData' => 'base64encodedAuthData',
            'signature' => 'base64encodedSignature',
            'userHandle' => 'base64encodedUserHandle',
        ],
    );

    $response = $connector->loginWithPasskey($dto);

    $mockClient->assertSent(AuthenticatePasskeyRequest::class);
    expect($response->successful())->toBeTrue();
});

it('returns auth dto on successful passkey login', function () {
    $mockClient = new MockClient([
        AuthenticatePasskeyRequest::class => MockResponse::fixture('Auth/authenticate_passkey'),
    ]);

    $connector = new OdooConnector('https://demo.odoo.com', 'demo');
    $connector->withMockClient($mockClient);

    $dto = new AuthenticatePasskeyDto(
        id: 'credentialId123',
        rawId: 'rawCredentialId123',
        type: 'public-key',
        response: [
            'clientDataJSON' => 'base64encodedClientData',
            'authenticatorData' => 'base64encodedAuthData',
            'signature' => 'base64encodedSignature',
            'userHandle' => 'base64encodedUserHandle',
        ],
    );

    $response = $connector->loginWithPasskey($dto);
    $authDto = $response->dto();

    expect($authDto)->not->toBeNull()
        ->and($authDto->uid)->toBe(1)
        ->and($authDto->sessionId)->toBe('abc123sessiontoken');
});
