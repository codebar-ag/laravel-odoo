<?php

use CodebarAg\Odoo\Responses\Api\Permissions\PermissionsResponse;
it('checks read permissions on account.analytic.line', function () {
    $response = PermissionsResponse::fromResponse(
        $this->connector()->getPermissions(
            model: 'account.analytic.line',
            operation: 'read',
        )
    );

    expect($response->successful())->toBeTrue()
        ->and($response->body())->toBeJson();
})->group('live');
