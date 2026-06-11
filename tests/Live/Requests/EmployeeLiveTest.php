<?php

it('gets employee by user id', function () {
    $response = $this->connector()->getEmployeeByUserId(userId: 2);

    expect($response->successful())->toBeTrue()
        ->and($response->body())->toBeJson();
})->group('live');
