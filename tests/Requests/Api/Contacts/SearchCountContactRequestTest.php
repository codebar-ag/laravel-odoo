<?php

use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\Api\Contacts\SearchCountContactRequest;
use CodebarAg\Odoo\Responses\Api\Contacts\SearchCountContactResponse;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('sends request to correct endpoint', function () {
    $mockClient = new MockClient([
        SearchCountContactRequest::class => MockResponse::fixture('Api/Contacts/search-count-contact'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = SearchCountContactResponse::fromResponse(
        $connector->send(new SearchCountContactRequest([['is_company', '=', true]]))
    );

    $mockClient->assertSent(SearchCountContactRequest::class);
    expect($response->successful())->toBeTrue();
});

it('sends correct domain body', function () {
    $mockClient = new MockClient([
        SearchCountContactRequest::class => MockResponse::fixture('Api/Contacts/search-count-contact'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $connector->send(new SearchCountContactRequest([['is_company', '=', true]]));

    $mockClient->assertSent(function (SearchCountContactRequest $request) {
        $body = $request->body()->all();

        return data_get($body, 'domain') === [['is_company', '=', true]];
    });
});

it('sends empty domain when none provided', function () {
    $mockClient = new MockClient([
        SearchCountContactRequest::class => MockResponse::fixture('Api/Contacts/search-count-contact'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $connector->send(new SearchCountContactRequest);

    $mockClient->assertSent(function (SearchCountContactRequest $request) {
        return data_get($request->body()->all(), 'domain') === [];
    });
});

it('returns count from response', function () {
    $mockClient = new MockClient([
        SearchCountContactRequest::class => MockResponse::fixture('Api/Contacts/search-count-contact'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = SearchCountContactResponse::fromResponse(
        $connector->send(new SearchCountContactRequest)
    );

    expect($response->count())->toBe(12);
});
