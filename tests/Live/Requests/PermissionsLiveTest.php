<?php

it('checks read permissions on account.analytic.line', function () {
    $response = $this->connector()->getPermissions(
        model: 'account.analytic.line',
        operation: 'read',
    );

    expect($response->successful())->toBeTrue()
        ->and($response->body())->toBeJson();
})->group('live');
