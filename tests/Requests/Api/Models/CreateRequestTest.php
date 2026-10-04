<?php

use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\Api\Models\CreateRequest;
use CodebarAg\Odoo\Responses\Api\Models\CreateResponse;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('sends request to correct endpoint for given model', function () {
    $mockClient = new MockClient([
        CreateRequest::class => MockResponse::fixture('Api/Models/create'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = CreateResponse::fromResponse(
        $connector->send(new CreateRequest('crm.lead', ['name' => 'Portal enquiry']))
    );

    $mockClient->assertSent(function (CreateRequest $request) {
        return $request->resolveEndpoint() === '/json/2/crm.lead/create';
    });
    expect($response->successful())->toBeTrue();
});

it('wraps values in vals_list body', function () {
    $mockClient = new MockClient([
        CreateRequest::class => MockResponse::fixture('Api/Models/create'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $connector->send(new CreateRequest('crm.lead', ['name' => 'Portal enquiry', 'partner_id' => 7]));

    $mockClient->assertSent(function (CreateRequest $request) {
        return $request->body()->all() === [
            'vals_list' => [['name' => 'Portal enquiry', 'partner_id' => 7]],
        ];
    });
});

it('returns new id via connector', function () {
    $mockClient = new MockClient([
        CreateRequest::class => MockResponse::fixture('Api/Models/create'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    expect($connector->create('crm.lead', ['name' => 'Portal enquiry'])->id())->toBe(42);
});

it('accepts a bare id result', function () {
    $mockClient = new MockClient([
        CreateRequest::class => MockResponse::make('42', 200, ['Content-Type' => 'application/json']),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    expect($connector->create('crm.lead', ['name' => 'Portal enquiry'])->id())->toBe(42);
});

it('returns null id when the request failed', function () {
    $mockClient = new MockClient([
        CreateRequest::class => MockResponse::fixture('Api/Models/call-method-failed'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    expect($connector->create('crm.lead', ['name' => 'Portal enquiry'])->id())->toBeNull();
});
