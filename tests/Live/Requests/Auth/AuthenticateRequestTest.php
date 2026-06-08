<?php

use CodebarAg\Odoo\Dto\Auth\AuthenticateDto;

it('authenticates with odoo using credentials from env', function () {
    $connector = $this->connector();

    $response = $connector->login(new AuthenticateDto(
        db: $connector->getDb(),
        login: $this->login(),
        password: $this->password(),
    ));

    expect($response->successful())->toBeTrue()
        ->and($response->dto())->not->toBeNull()
        ->and($response->dto()->uid)->toBeGreaterThan(0);
})->group('live');
