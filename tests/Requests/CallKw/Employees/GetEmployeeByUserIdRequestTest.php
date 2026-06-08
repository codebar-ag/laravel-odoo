<?php

use CodebarAg\Odoo\OdooConnector;
use CodebarAg\Odoo\Requests\CallKw\Employees\GetEmployeeByUserIdRequest;
use CodebarAg\Odoo\Responses\CallKw\Employees\EmployeeResponse;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('sends request to correct endpoint', function () {
    $mockClient = new MockClient([
        GetEmployeeByUserIdRequest::class => MockResponse::fixture('CallKw/Employees/get_employee_by_user_id'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'demo');
    $connector->withMockClient($mockClient);

    $response = EmployeeResponse::fromResponse(
        $connector->send(new GetEmployeeByUserIdRequest(5))
    );

    $mockClient->assertSent(GetEmployeeByUserIdRequest::class);
    expect($response->successful())->toBeTrue();
});

it('sends correct json-rpc body', function () {
    $mockClient = new MockClient([
        GetEmployeeByUserIdRequest::class => MockResponse::fixture('CallKw/Employees/get_employee_by_user_id'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'demo');
    $connector->withMockClient($mockClient);

    $connector->send(new GetEmployeeByUserIdRequest(5));

    $mockClient->assertSent(function (GetEmployeeByUserIdRequest $request) {
        $body = $request->body()->all();

        return $body['jsonrpc'] === '2.0'
            && $body['method'] === 'call'
            && $body['params']['model'] === 'hr.employee'
            && isset($body['params']['args'])
            && isset($body['params']['kwargs']);
    });
});

it('parses response correctly', function () {
    $mockClient = new MockClient([
        GetEmployeeByUserIdRequest::class => MockResponse::fixture('CallKw/Employees/get_employee_by_user_id'),
    ]);
    $connector = new OdooConnector('https://demo.odoo.com', 'demo');
    $connector->withMockClient($mockClient);

    $response = EmployeeResponse::fromResponse(
        $connector->send(new GetEmployeeByUserIdRequest(5))
    );

    $dto = $response->dto();

    expect($dto)->not->toBeNull();
    expect($dto->id)->toBe(1);
    expect($dto->name)->toBe('John Doe');
});
