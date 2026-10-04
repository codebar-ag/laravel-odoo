<?php

use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\Api\Models\CallMethodRequest;
use CodebarAg\Odoo\Responses\Api\Models\CallMethodResponse;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('sends request to model method endpoint', function () {
    $mockClient = new MockClient([
        CallMethodRequest::class => MockResponse::fixture('Api/Models/call-method'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = CallMethodResponse::fromResponse(
        $connector->send(new CallMethodRequest('sale.order', 'get_portal_url', ['ids' => [12]]))
    );

    $mockClient->assertSent(function (CallMethodRequest $request) {
        return $request->resolveEndpoint() === '/json/2/sale.order/get_portal_url';
    });
    expect($response->successful())->toBeTrue();
});

it('sends params unchanged as body', function () {
    $mockClient = new MockClient([
        CallMethodRequest::class => MockResponse::fixture('Api/Models/call-method'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $params = ['ids' => [12], 'report_type' => 'pdf', 'download' => true];

    $connector->send(new CallMethodRequest('sale.order', 'get_portal_url', $params));

    $mockClient->assertSent(function (CallMethodRequest $request) use ($params) {
        return $request->body()->all() === $params;
    });
});

it('returns a scalar result via connector', function () {
    $mockClient = new MockClient([
        CallMethodRequest::class => MockResponse::fixture('Api/Models/call-method'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = $connector->callMethod('sale.order', 'get_portal_url', ['ids' => [12], 'report_type' => 'pdf', 'download' => true]);

    expect($response->result())->toBe('/my/orders/12?access_token=abc&report_type=pdf&download=true');
});

it('returns boolean and list results', function (string $body, mixed $expected) {
    $mockClient = new MockClient([
        CallMethodRequest::class => MockResponse::make($body, 200, ['Content-Type' => 'application/json']),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    expect($connector->callMethod('sale.order', 'action_confirm', ['ids' => [12]])->result())->toBe($expected);
})->with([
    'true' => ['true', true],
    'false' => ['false', false],
    'list' => ['[1, 2]', [1, 2]],
    'object' => ['{"id": 1}', ['id' => 1]],
]);

it('returns null result when the request failed', function () {
    $mockClient = new MockClient([
        CallMethodRequest::class => MockResponse::fixture('Api/Models/call-method-failed'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = $connector->callMethod('sale.order', 'action_confirm', ['ids' => [12]]);

    expect($response->failed())->toBeTrue()
        ->and($response->status())->toBe(403)
        ->and($response->result())->toBeNull();
});
