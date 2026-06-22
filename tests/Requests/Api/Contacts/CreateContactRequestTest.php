<?php

use CodebarAg\Odoo\Dto\Contacts\CreateContactDto;
use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\Api\Contacts\CreateContactRequest;
use CodebarAg\Odoo\Responses\Api\Contacts\CreateContactResponse;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('sends request to correct endpoint', function () {
    $mockClient = new MockClient([
        CreateContactRequest::class => MockResponse::fixture('Api/Contacts/create-contact'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $dto = new CreateContactDto(name: 'Test Contact');

    $response = CreateContactResponse::fromResponse(
        $connector->send(new CreateContactRequest($dto))
    );

    $mockClient->assertSent(CreateContactRequest::class);
    expect($response->successful())->toBeTrue();
});

it('sends correct vals_list body', function () {
    $mockClient = new MockClient([
        CreateContactRequest::class => MockResponse::fixture('Api/Contacts/create-contact'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $dto = new CreateContactDto(
        name: 'Test Contact',
        isCompany: true,
        email: 'test@example.com',
        phone: '+41 44 000 00 00',
        city: 'Zurich',
    );

    $connector->send(new CreateContactRequest($dto));

    $mockClient->assertSent(function (CreateContactRequest $request) {
        $body = $request->body()->all();

        return data_get($body, 'vals_list.name') === 'Test Contact'
            && data_get($body, 'vals_list.is_company') === true
            && data_get($body, 'vals_list.email') === 'test@example.com'
            && data_get($body, 'vals_list.phone') === '+41 44 000 00 00'
            && data_get($body, 'vals_list.city') === 'Zurich';
    });
});

it('returns the created contact id', function () {
    $mockClient = new MockClient([
        CreateContactRequest::class => MockResponse::fixture('Api/Contacts/create-contact'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $dto = new CreateContactDto(name: 'Test Contact');

    $response = CreateContactResponse::fromResponse(
        $connector->send(new CreateContactRequest($dto))
    );

    expect($response->id())->toBe(42);
});
