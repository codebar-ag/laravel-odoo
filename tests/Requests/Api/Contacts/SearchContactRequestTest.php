<?php

use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\Api\Contacts\SearchContactRequest;
use CodebarAg\Odoo\Responses\Api\Contacts\SearchContactResponse;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('sends request to correct endpoint', function () {
    $mockClient = new MockClient([
        SearchContactRequest::class => MockResponse::fixture('Api/Contacts/search-contact'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = SearchContactResponse::fromResponse(
        $connector->send(new SearchContactRequest([['name', '=', 'Max Mustermann']]))
    );

    $mockClient->assertSent(SearchContactRequest::class);
    expect($response->successful())->toBeTrue();
});

it('sends correct domain body', function () {
    $mockClient = new MockClient([
        SearchContactRequest::class => MockResponse::fixture('Api/Contacts/search-contact'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $connector->send(new SearchContactRequest([['email', '=', 'max@mustermann.ch']]));

    $mockClient->assertSent(function (SearchContactRequest $request) {
        $body = $request->body()->all();

        return data_get($body, 'domain') === [['email', '=', 'max@mustermann.ch']];
    });
});

it('returns ids from response', function () {
    $mockClient = new MockClient([
        SearchContactRequest::class => MockResponse::fixture('Api/Contacts/search-contact'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = SearchContactResponse::fromResponse(
        $connector->send(new SearchContactRequest([['name', '=', 'Max Mustermann']]))
    );

    expect($response->ids())->toBe([42, 43]);
});
