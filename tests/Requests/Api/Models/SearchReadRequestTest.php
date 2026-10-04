<?php

use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\Api\Models\SearchReadRequest;
use CodebarAg\Odoo\Responses\Api\Models\SearchReadResponse;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('sends request to correct endpoint for given model', function () {
    $mockClient = new MockClient([
        SearchReadRequest::class => MockResponse::fixture('Api/Models/search-read'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = SearchReadResponse::fromResponse(
        $connector->send(new SearchReadRequest('sale.order', [['partner_id', '=', 7]], ['name', 'state']))
    );

    $mockClient->assertSent(function (SearchReadRequest $request) {
        return $request->resolveEndpoint() === '/json/2/sale.order/search_read';
    });
    expect($response->successful())->toBeTrue();
});

it('sends domain, fields, limit, offset and order in body', function () {
    $mockClient = new MockClient([
        SearchReadRequest::class => MockResponse::fixture('Api/Models/search-read'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $connector->send(new SearchReadRequest(
        model: 'sale.order',
        domain: [['partner_id', '=', 7]],
        fields: ['name', 'state'],
        limit: 20,
        offset: 40,
        order: 'date_order desc',
    ));

    $mockClient->assertSent(function (SearchReadRequest $request) {
        return $request->body()->all() === [
            'domain' => [['partner_id', '=', 7]],
            'fields' => ['name', 'state'],
            'limit' => 20,
            'offset' => 40,
            'order' => 'date_order desc',
        ];
    });
});

it('omits order from body when not given', function () {
    $mockClient = new MockClient([
        SearchReadRequest::class => MockResponse::fixture('Api/Models/search-read'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $connector->send(new SearchReadRequest('sale.order', [], ['name']));

    $mockClient->assertSent(function (SearchReadRequest $request) {
        return $request->body()->all() === [
            'domain' => [],
            'fields' => ['name'],
            'limit' => 80,
            'offset' => 0,
        ];
    });
});

it('returns records via connector', function () {
    $mockClient = new MockClient([
        SearchReadRequest::class => MockResponse::fixture('Api/Models/search-read'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $records = $connector->searchRead('sale.order', [['partner_id', '=', 7]], ['name', 'state'], order: 'id desc')->records();

    expect($records)->toHaveCount(2)
        ->and(data_get($records, '0.id'))->toBe(12)
        ->and(data_get($records, '0.name'))->toBe('S00012')
        ->and(data_get($records, '1.state'))->toBe('draft');
});

it('returns no records when the request failed', function () {
    $mockClient = new MockClient([
        SearchReadRequest::class => MockResponse::fixture('Api/Models/call-method-failed'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = $connector->searchRead('sale.order');

    expect($response->failed())->toBeTrue()
        ->and($response->records())->toBe([]);
});
