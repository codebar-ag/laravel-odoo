<?php

use CodebarAg\Odoo\Responses\Api\Employees\EmployeeResponse;

it('gets employee by user id', function () {
    $response = EmployeeResponse::fromResponse(
        $this->connector()->getEmployeeByUserId(userId: 2)
    );
    expect($response->successful())->toBeTrue()
        ->and($response->body())->toBeJson();
})->group('live');
