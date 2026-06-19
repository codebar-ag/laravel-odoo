<?php

use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\Api\Contacts\NameSearchContactRequest;
use CodebarAg\Odoo\Responses\Api\Contacts\NameSearchContactResponse;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('sends request to correct endpoint', function () {
    $mockClient = new MockClient([
        NameSearchContactRequest::class => MockResponse::fixture('Api/Contacts/name-search-contact'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = NameSearchContactResponse::fromResponse(
        $connector->send(new NameSearchContactRequest('Max'))
    );

    $mockClient->assertSent(NameSearchContactRequest::class);
    expect($response->successful())->toBeTrue();
});

it('sends correct name domain and limit body', function () {
    $mockClient = new MockClient([
        NameSearchContactRequest::class => MockResponse::fixture('Api/Contacts/name-search-contact'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $connector->send(new NameSearchContactRequest(
        name: 'Max',
        domain: [['is_company', '=', false]],
        limit: 10,
    ));

    $mockClient->assertSent(function (NameSearchContactRequest $request) {
        $body = $request->body()->all();

        return data_get($body, 'name') === 'Max'
            && data_get($body, 'domain') === [['is_company', '=', false]]
            && data_get($body, 'limit') === 10;
    });
});

it('returns id and name tuples from response', function () {
    $mockClient = new MockClient([
        NameSearchContactRequest::class => MockResponse::fixture('Api/Contacts/name-search-contact'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = NameSearchContactResponse::fromResponse(
        $connector->send(new NameSearchContactRequest('Max'))
    );

    expect($response->results())->toBe([[42, 'Max Mustermann'], [43, 'Max Mustermann GmbH']]);
});
