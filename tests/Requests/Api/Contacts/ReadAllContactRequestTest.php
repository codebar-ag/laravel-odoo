<?php

use CodebarAg\Odoo\Dto\Contacts\ContactDto;
use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\Api\Contacts\ReadAllContactRequest;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('sends request to correct endpoint', function () {
    $mockClient = new MockClient([
        ReadAllContactRequest::class => MockResponse::fixture('Api/Contacts/contacts'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = $connector->send(new ReadAllContactRequest());

    $mockClient->assertSent(ReadAllContactRequest::class);
    expect($response->successful())->toBeTrue();
});

it('sends empty domain and default fields body', function () {
    $mockClient = new MockClient([
        ReadAllContactRequest::class => MockResponse::fixture('Api/Contacts/contacts'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $connector->send(new ReadAllContactRequest());

    $mockClient->assertSent(function (ReadAllContactRequest $request) {
        $body = $request->body()->all();

        return data_get($body, 'domain') === []
            && in_array('id', data_get($body, 'fields'))
            && in_array('name', data_get($body, 'fields'));
    });
});

it('sends custom fields when provided', function () {
    $mockClient = new MockClient([
        ReadAllContactRequest::class => MockResponse::fixture('Api/Contacts/contacts'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $connector->send(new ReadAllContactRequest(fields: ['id', 'name', 'email']));

    $mockClient->assertSent(function (ReadAllContactRequest $request) {
        $body = $request->body()->all();

        return data_get($body, 'fields') === ['id', 'name', 'email'];
    });
});

it('maps response to ContactDto collection', function () {
    $mockClient = new MockClient([
        ReadAllContactRequest::class => MockResponse::fixture('Api/Contacts/contacts'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = $connector->send(new ReadAllContactRequest());

    $contacts = collect($response->json())
        ->map(fn (array $record) => ContactDto::fromArray($record))
        ->all();

    expect($contacts)->toHaveCount(2);
    expect($contacts[0])->toBeInstanceOf(ContactDto::class);
    expect($contacts[0]->id)->toBe(42);
    expect($contacts[0]->name)->toBe('Fixture Contact');
    expect($contacts[0]->isCompany)->toBeFalse();
    expect($contacts[1]->id)->toBe(43);
    expect($contacts[1]->name)->toBe('Fixture Company');
    expect($contacts[1]->isCompany)->toBeTrue();
});
