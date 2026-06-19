<?php

use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\Api\Contacts\DeleteContactRequest;
use CodebarAg\Odoo\Responses\Api\Contacts\MutateContactResponse;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('sends request to correct endpoint', function () {
    $mockClient = new MockClient([
        DeleteContactRequest::class => MockResponse::fixture('Api/Contacts/delete-contact'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = MutateContactResponse::fromResponse(
        $connector->send(new DeleteContactRequest(42))
    );

    $mockClient->assertSent(DeleteContactRequest::class);
    expect($response->successful())->toBeTrue();
});

it('sends correct ids body', function () {
    $mockClient = new MockClient([
        DeleteContactRequest::class => MockResponse::fixture('Api/Contacts/delete-contact'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $connector->send(new DeleteContactRequest(42));

    $mockClient->assertSent(function (DeleteContactRequest $request) {
        $body = $request->body()->all();

        return data_get($body, 'ids') === [42];
    });
});

it('confirms successful deletion', function () {
    $mockClient = new MockClient([
        DeleteContactRequest::class => MockResponse::fixture('Api/Contacts/delete-contact'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = MutateContactResponse::fromResponse(
        $connector->send(new DeleteContactRequest(42))
    );

    expect($response->ok())->toBeTrue();
});
