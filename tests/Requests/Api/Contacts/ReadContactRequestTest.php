<?php

use CodebarAg\Odoo\Dto\Contacts\ContactDto;
use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\Api\Contacts\ReadContactRequest;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('sends request to correct endpoint', function () {
    $mockClient = new MockClient([
        ReadContactRequest::class => MockResponse::fixture('Api/Contacts/contact'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = $connector->send(new ReadContactRequest(42));

    $mockClient->assertSent(ReadContactRequest::class);
    expect($response->successful())->toBeTrue();
});

it('sends correct ids and fields body', function () {
    $mockClient = new MockClient([
        ReadContactRequest::class => MockResponse::fixture('Api/Contacts/contact'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $connector->send(new ReadContactRequest(42));

    $mockClient->assertSent(function (ReadContactRequest $request) {
        $body = $request->body()->all();

        return data_get($body, 'ids') === [42]
            && in_array('id', data_get($body, 'fields'))
            && in_array('name', data_get($body, 'fields'));
    });
});

it('maps response to ContactDto', function () {
    $mockClient = new MockClient([
        ReadContactRequest::class => MockResponse::fixture('Api/Contacts/contact'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = $connector->send(new ReadContactRequest(42));
    $contact = ContactDto::fromArray($response->json()[0]);

    expect($contact)->toBeInstanceOf(ContactDto::class);
    expect($contact->id)->toBe(42);
    expect($contact->name)->toBe('Fixture Contact');
    expect($contact->email)->toBe('fixture@example.com');
    expect($contact->city)->toBe('Zurich');
    expect($contact->countryId)->toBe(176);
    expect($contact->countryName)->toBe('Switzerland');
    expect($contact->isCompany)->toBeFalse();
});
