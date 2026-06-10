<?php

use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\Api\Employees\GetEmployeeByUserIdRequest;
use CodebarAg\Odoo\Responses\Api\Employees\EmployeeResponse;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('sends request to correct endpoint', function () {
    $mockClient = new MockClient([
        GetEmployeeByUserIdRequest::class => MockResponse::fixture('Api/Employees/employee'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = EmployeeResponse::fromResponse(
        $connector->send(new GetEmployeeByUserIdRequest(2))
    );

    $mockClient->assertSent(GetEmployeeByUserIdRequest::class);
    expect($response->successful())->toBeTrue();
});

it('sends correct body with user id domain and limit 1', function () {
    $mockClient = new MockClient([
        GetEmployeeByUserIdRequest::class => MockResponse::fixture('Api/Employees/employee'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $connector->send(new GetEmployeeByUserIdRequest(42));

    $mockClient->assertSent(function (GetEmployeeByUserIdRequest $request) {
        $body = $request->body()->all();

        return $body['domain'] === [['user_id', '=', 42]]
            && $body['limit'] === 1
            && in_array('id', $body['fields'])
            && in_array('name', $body['fields']);
    });
});

it('parses employee from response', function () {
    $mockClient = new MockClient([
        GetEmployeeByUserIdRequest::class => MockResponse::fixture('Api/Employees/employee'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'api-key-123');
    $connector->withMockClient($mockClient);

    $response = EmployeeResponse::fromResponse(
        $connector->send(new GetEmployeeByUserIdRequest(2))
    );

    $employee = $response->dto();

    expect($employee)->not->toBeNull();
    expect($employee->id)->toBe(1);
    expect($employee->name)->toBe('John Doe');
});
