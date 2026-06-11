<?php

use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\Session\Health\HealthRequest;
use CodebarAg\Odoo\Responses\Session\HealthResponse;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

function healthResponseFor(array $body, int $status): HealthResponse
{
    $mockClient = new MockClient([
        HealthRequest::class => MockResponse::make($body, $status),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    return HealthResponse::fromResponse($connector->send(new HealthRequest));
}

it('extracts a nested error message from a failed response', function () {
    $response = healthResponseFor([
        'error' => [
            'code' => 404,
            'message' => 'Odoo Server Error',
            'data' => ['message' => 'Record does not exist'],
        ],
    ], 404);

    expect($response->successful())->toBeFalse()
        ->and($response->failed())->toBeTrue()
        ->and($response->status())->toBe(404)
        ->and($response->error())->toBe('Record does not exist');
});

it('prefers error_description when present', function () {
    $response = healthResponseFor(['error_description' => 'Invalid API key'], 401);

    expect($response->error())->toBe('Invalid API key')
        ->and($response->status())->toBe(401);
});

it('returns a string error code when the error is a string', function () {
    $response = healthResponseFor(['error' => 'access_denied'], 403);

    expect($response->errorCode())->toBe('access_denied');
});

it('returns null error helpers on a successful response', function () {
    $response = healthResponseFor(['status' => 'pass'], 200);

    expect($response->error())->toBeNull()
        ->and($response->errorCode())->toBeNull()
        ->and($response->isHealthy())->toBeTrue();
});
