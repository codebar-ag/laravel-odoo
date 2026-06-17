<?php

use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\Api\User\GetUserContextRequest;
use CodebarAg\Odoo\Responses\Api\User\UserContextResponse;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('sends request to correct endpoint', function () {
    $mockClient = new MockClient([
        GetUserContextRequest::class => MockResponse::fixture('Api/User/user_context'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = $connector->send(new GetUserContextRequest);

    $mockClient->assertSent(GetUserContextRequest::class);
    expect($response->successful())->toBeTrue();
});

it('parses user context into a dto', function () {
    $mockClient = new MockClient([
        GetUserContextRequest::class => MockResponse::fixture('Api/User/user_context'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = UserContextResponse::fromResponse(
        $connector->send(new GetUserContextRequest)
    );

    $dto = $response->dto();

    expect($dto)->not->toBeNull();
    expect($dto->id)->toBe(2);
    expect($dto->tz)->toBe('Europe/Zurich');
    expect($dto->lang)->toBe('en_US');
});
