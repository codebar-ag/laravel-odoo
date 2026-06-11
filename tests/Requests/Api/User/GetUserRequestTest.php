<?php

use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\Api\User\GetUserRequest;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('sends request to correct endpoint', function () {
    $mockClient = new MockClient([
        GetUserRequest::class => MockResponse::fixture('Api/User/user'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = $connector->send(new GetUserRequest());

    $mockClient->assertSent(GetUserRequest::class);
    expect($response->successful())->toBeTrue();
});

it('sends correct body with id name lang fields and limit 1', function () {
    $mockClient = new MockClient([
        GetUserRequest::class => MockResponse::fixture('Api/User/user'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $connector->send(new GetUserRequest());

    $mockClient->assertSent(function (GetUserRequest $request) {
        $body = $request->body()->all();

        return $body['limit'] === 1
            && in_array('id', $body['fields'])
            && in_array('name', $body['fields'])
            && in_array('lang', $body['fields']);
    });
});

it('parses user data from response', function () {
    $mockClient = new MockClient([
        GetUserRequest::class => MockResponse::fixture('Api/User/user'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = $connector->send(new GetUserRequest());
    $users = $response->json();

    expect($users)->not->toBeEmpty();
    expect($users[0]['id'])->toBe(2);
    expect($users[0]['name'])->toBe('Administrator');
    expect($users[0]['lang'])->toBe('en_US');
});
