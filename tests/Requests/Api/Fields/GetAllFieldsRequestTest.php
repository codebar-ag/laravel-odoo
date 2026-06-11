<?php

use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\Api\Fields\GetAllFieldsRequest;
use CodebarAg\Odoo\Responses\Api\Fields\FieldsResponse;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('sends request to correct endpoint', function () {
    $mockClient = new MockClient([
        GetAllFieldsRequest::class => MockResponse::fixture('Api/Fields/fields'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = FieldsResponse::fromResponse(
        $connector->send(new GetAllFieldsRequest)
    );

    $mockClient->assertSent(GetAllFieldsRequest::class);
    expect($response->successful())->toBeTrue();
});

it('sends correct attributes in body', function () {
    $mockClient = new MockClient([
        GetAllFieldsRequest::class => MockResponse::fixture('Api/Fields/fields'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $connector->send(new GetAllFieldsRequest);

    $mockClient->assertSent(function (GetAllFieldsRequest $request) {
        $body = $request->body()->all();

        return $body['attributes'] === ['string', 'type', 'required'];
    });
});

it('parses fields from response', function () {
    $mockClient = new MockClient([
        GetAllFieldsRequest::class => MockResponse::fixture('Api/Fields/fields'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = FieldsResponse::fromResponse(
        $connector->send(new GetAllFieldsRequest)
    );

    $fields = $response->fields();

    expect($fields)->toHaveKey('name');
    expect($fields)->toHaveKey('date');
    expect($fields['name']->type)->toBe('char');
    expect($fields['date']->type)->toBe('date');
});
