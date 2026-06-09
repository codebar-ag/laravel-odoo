<?php

use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\CallKw\Fields\GetAllFieldsRequest;
use CodebarAg\Odoo\Responses\CallKw\Fields\FieldsResponse;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('sends request to correct endpoint', function () {
    $mockClient = new MockClient([
        GetAllFieldsRequest::class => MockResponse::fixture('CallKw/Fields/get_all_fields'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'demo');
    $connector->withMockClient($mockClient);

    $response = FieldsResponse::fromResponse(
        $connector->send(new GetAllFieldsRequest)
    );

    $mockClient->assertSent(GetAllFieldsRequest::class);
    expect($response->successful())->toBeTrue();
});

it('sends correct json-rpc body', function () {
    $mockClient = new MockClient([
        GetAllFieldsRequest::class => MockResponse::fixture('CallKw/Fields/get_all_fields'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'demo');
    $connector->withMockClient($mockClient);

    $connector->send(new GetAllFieldsRequest);

    $mockClient->assertSent(function (GetAllFieldsRequest $request) {
        $body = $request->body()->all();

        return $body['jsonrpc'] === '2.0'
            && $body['method'] === 'call'
            && isset($body['params']['model'])
            && isset($body['params']['args'])
            && isset($body['params']['kwargs']);
    });
});

it('parses response correctly', function () {
    $mockClient = new MockClient([
        GetAllFieldsRequest::class => MockResponse::fixture('CallKw/Fields/get_all_fields'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'demo');
    $connector->withMockClient($mockClient);

    $response = FieldsResponse::fromResponse(
        $connector->send(new GetAllFieldsRequest)
    );

    $fields = $response->fields();

    expect($fields)->toHaveKey('name');
    expect($fields)->toHaveKey('date');
    expect($fields)->toHaveKey('unit_amount');
});
