<?php

use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\Api\Fields\GetFieldsRequest;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('routes getAllFields through the account.analytic.line model', function () {
    $mockClient = new MockClient([
        GetFieldsRequest::class => MockResponse::fixture('Api/Fields/fields'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = $connector->getAllFields();

    $mockClient->assertSent(function (GetFieldsRequest $request) {
        return str_contains($request->resolveEndpoint(), 'account.analytic.line');
    });
    expect($response->successful())->toBeTrue();
});

it('sends correct attributes in body', function () {
    $mockClient = new MockClient([
        GetFieldsRequest::class => MockResponse::fixture('Api/Fields/fields'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $connector->getAllFields();

    $mockClient->assertSent(function (GetFieldsRequest $request) {
        $body = $request->body()->all();

        return data_get($body, 'attributes') === ['string', 'type', 'required', 'readonly', 'relation'];
    });
});

it('parses fields from response', function () {
    $mockClient = new MockClient([
        GetFieldsRequest::class => MockResponse::fixture('Api/Fields/fields'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $fields = $connector->getAllFields()->fields();

    expect($fields)->toHaveKey('name');
    expect($fields)->toHaveKey('date');
    expect(data_get($fields, 'name')->type)->toBe('char');
    expect(data_get($fields, 'date')->type)->toBe('date');
});
