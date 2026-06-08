<?php

use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\Auth\GetPasskeyOptionsRequest;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('sends get passkey options request to correct endpoint', function () {
    $mockClient = new MockClient([
        GetPasskeyOptionsRequest::class => MockResponse::fixture('Auth/passkey_options'),
    ]);

    $connector = new OdooConnector('https://demo.odoo.com', 'demo');
    $connector->withMockClient($mockClient);

    $response = $connector->getPasskeyOptions();

    $mockClient->assertSent(GetPasskeyOptionsRequest::class);
    expect($response->successful())->toBeTrue();
});

it('returns passkey options dto with challenge', function () {
    $mockClient = new MockClient([
        GetPasskeyOptionsRequest::class => MockResponse::fixture('Auth/passkey_options'),
    ]);

    $connector = new OdooConnector('https://demo.odoo.com', 'demo');
    $connector->withMockClient($mockClient);

    $response = $connector->getPasskeyOptions();
    $dto = $response->dto();

    expect($dto)->not->toBeNull()
        ->and($dto->challenge)->toBe('dGhpcyBpcyBhIHRlc3QgY2hhbGxlbmdl')
        ->and($dto->rpId)->toBe('mycompany.odoo.com')
        ->and($dto->timeout)->toBe(60000)
        ->and($dto->allowCredentials)->toHaveCount(1);
});
