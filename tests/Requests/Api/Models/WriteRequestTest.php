<?php

use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\Api\Models\WriteRequest;
use CodebarAg\Odoo\Responses\Api\Models\WriteResponse;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('sends request to correct endpoint for given model', function () {
    $mockClient = new MockClient([
        WriteRequest::class => MockResponse::fixture('Api/Models/write'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = WriteResponse::fromResponse(
        $connector->send(new WriteRequest('res.partner', [7], ['phone' => '+41 44 000 00 00']))
    );

    $mockClient->assertSent(function (WriteRequest $request) {
        return $request->resolveEndpoint() === '/json/2/res.partner/write';
    });
    expect($response->successful())->toBeTrue();
});

it('sends ids and vals in body', function () {
    $mockClient = new MockClient([
        WriteRequest::class => MockResponse::fixture('Api/Models/write'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $connector->send(new WriteRequest('res.partner', [7, 8], ['phone' => '+41 44 000 00 00']));

    $mockClient->assertSent(function (WriteRequest $request) {
        return $request->body()->all() === [
            'ids' => [7, 8],
            'vals' => ['phone' => '+41 44 000 00 00'],
        ];
    });
});

it('confirms successful write via connector', function () {
    $mockClient = new MockClient([
        WriteRequest::class => MockResponse::fixture('Api/Models/write'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    expect($connector->write('res.partner', [7], ['phone' => '+41 44 000 00 00'])->ok())->toBeTrue();
});

it('is not ok when the request failed', function () {
    $mockClient = new MockClient([
        WriteRequest::class => MockResponse::fixture('Api/Models/call-method-failed'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = $connector->write('res.partner', [7], ['phone' => '+41 44 000 00 00']);

    expect($response->ok())->toBeFalse()
        ->and($response->status())->toBe(403);
});
