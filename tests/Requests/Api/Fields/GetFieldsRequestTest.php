<?php

use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\Api\Fields\GetFieldsRequest;
use CodebarAg\Odoo\Responses\Api\Fields\FieldsResponse;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('sends request to correct endpoint for given model', function () {
    $mockClient = new MockClient([
        GetFieldsRequest::class => MockResponse::fixture('Api/Fields/fields'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = FieldsResponse::fromResponse(
        $connector->send(new GetFieldsRequest('project.project'))
    );

    $mockClient->assertSent(GetFieldsRequest::class);
    expect($response->successful())->toBeTrue();
});

it('includes model name in endpoint', function () {
    $mockClient = new MockClient([
        GetFieldsRequest::class => MockResponse::fixture('Api/Fields/fields'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $connector->send(new GetFieldsRequest('project.project'));

    $mockClient->assertSent(function (GetFieldsRequest $request) {
        return str_contains($request->resolveEndpoint(), 'project.project');
    });
});

it('sends custom attributes in body', function () {
    $mockClient = new MockClient([
        GetFieldsRequest::class => MockResponse::fixture('Api/Fields/fields'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $connector->send(new GetFieldsRequest('project.project', ['string', 'type']));

    $mockClient->assertSent(function (GetFieldsRequest $request) {
        $body = $request->body()->all();

        return data_get($body, 'attributes') === ['string', 'type'];
    });
});

it('parses fields from response', function () {
    $mockClient = new MockClient([
        GetFieldsRequest::class => MockResponse::fixture('Api/Fields/fields'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = FieldsResponse::fromResponse(
        $connector->send(new GetFieldsRequest('account.analytic.line'))
    );

    $fields = $response->fields();

    expect($fields)->toHaveKey('name');
    expect(data_get($fields, 'name')->type)->toBe('char');
    expect(data_get($fields, 'name')->label)->toBe('Description');
    expect(data_get($fields, 'date')->type)->toBe('date');
});
