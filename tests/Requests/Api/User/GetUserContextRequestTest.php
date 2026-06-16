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

it('sends correct body with id tz lang fields and limit 1', function () {
    $mockClient = new MockClient([
        GetUserContextRequest::class => MockResponse::fixture('Api/User/user_context'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $connector->send(new GetUserContextRequest);

    $mockClient->assertSent(function (GetUserContextRequest $request) {
        $body = $request->body()->all();

        return data_get($body, 'domain') === []
            && data_get($body, 'limit') === 1
            && in_array('id', data_get($body, 'fields', []))
            && in_array('tz', data_get($body, 'fields', []))
            && in_array('lang', data_get($body, 'fields', []));
    });
});

it('sends custom fields, domain and limit when provided', function () {
    $mockClient = new MockClient([
        GetUserContextRequest::class => MockResponse::fixture('Api/User/user_context'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $connector->send(new GetUserContextRequest(
        fields: ['id', 'tz'],
        domain: [['id', '=', 2]],
        limit: 2,
    ));

    $mockClient->assertSent(function (GetUserContextRequest $request) {
        $body = $request->body()->all();

        return data_get($body, 'fields') === ['id', 'tz']
            && data_get($body, 'domain') === [['id', '=', 2]]
            && data_get($body, 'limit') === 2;
    });
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
