<?php

use CodebarAg\Odoo\Dto\Contacts\UpdateContactDto;
use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\Api\Contacts\UpdateContactRequest;
use CodebarAg\Odoo\Responses\Api\Contacts\MutateContactResponse;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('sends request to correct endpoint', function () {
    $mockClient = new MockClient([
        UpdateContactRequest::class => MockResponse::fixture('Api/Contacts/mutate-contact'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $dto = new UpdateContactDto(id: 42, name: 'Updated Name');

    $response = MutateContactResponse::fromResponse(
        $connector->send(new UpdateContactRequest($dto))
    );

    $mockClient->assertSent(UpdateContactRequest::class);
    expect($response->successful())->toBeTrue();
});

it('sends correct ids and vals body', function () {
    $mockClient = new MockClient([
        UpdateContactRequest::class => MockResponse::fixture('Api/Contacts/mutate-contact'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $dto = new UpdateContactDto(id: 42, name: 'Updated Name', email: 'new@example.com');

    $connector->send(new UpdateContactRequest($dto));

    $mockClient->assertSent(function (UpdateContactRequest $request) {
        $body = $request->body()->all();

        return data_get($body, 'ids') === [42]
            && data_get($body, 'vals.name') === 'Updated Name'
            && data_get($body, 'vals.email') === 'new@example.com';
    });
});

it('confirms successful update', function () {
    $mockClient = new MockClient([
        UpdateContactRequest::class => MockResponse::fixture('Api/Contacts/mutate-contact'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $dto = new UpdateContactDto(id: 42, name: 'Updated Name');

    $response = MutateContactResponse::fromResponse(
        $connector->send(new UpdateContactRequest($dto))
    );

    expect($response->ok())->toBeTrue();
});
