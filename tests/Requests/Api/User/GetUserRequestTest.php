<?php

use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\Api\User\GetUserRequest;
use CodebarAg\Odoo\Responses\Api\User\UserResponse;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('sends request to correct endpoint', function () {
    $mockClient = new MockClient([
        GetUserRequest::class => MockResponse::fixture('Api/User/user'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = $connector->send(new GetUserRequest);

    $mockClient->assertSent(GetUserRequest::class);
    expect($response->successful())->toBeTrue();
});

it('sends correct body with id name lang fields and limit 1', function () {
    $mockClient = new MockClient([
        GetUserRequest::class => MockResponse::fixture('Api/User/user'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $connector->send(new GetUserRequest);

    $mockClient->assertSent(function (GetUserRequest $request) {
        $body = $request->body()->all();

        return data_get($body, 'limit') === 1
            && in_array('id', data_get($body, 'fields', []))
            && in_array('name', data_get($body, 'fields', []))
            && in_array('lang', data_get($body, 'fields', []));
    });
});

it('sends custom fields, domain and limit when provided', function () {
    $mockClient = new MockClient([
        GetUserRequest::class => MockResponse::fixture('Api/User/user'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $connector->send(new GetUserRequest(
        fields: ['id', 'name'],
        domain: [['active', '=', true]],
        limit: 5,
    ));

    $mockClient->assertSent(function (GetUserRequest $request) {
        $body = $request->body()->all();

        return data_get($body, 'fields') === ['id', 'name']
            && data_get($body, 'domain') === [['active', '=', true]]
            && data_get($body, 'limit') === 5;
    });
});

it('parses user data into a dto', function () {
    $mockClient = new MockClient([
        GetUserRequest::class => MockResponse::fixture('Api/User/user'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = UserResponse::fromResponse(
        $connector->send(new GetUserRequest)
    );

    $dto = $response->dto();

    expect($dto)->not->toBeNull();
    expect($dto->id)->toBe(2);
    expect($dto->name)->toBe('Administrator');
    expect($dto->lang)->toBe('en_US');
    expect($dto->login)->toBe('admin');
    expect($dto->email)->toBe('admin@example.com');
    expect($dto->tz)->toBe('Europe/Zurich');
    expect($dto->active)->toBeTrue();
    expect($dto->share)->toBeFalse();
    expect($dto->companyName)->toBe('MyCompany');
    expect($dto->employeeId)->toBe(1);
});
