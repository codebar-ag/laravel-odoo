<?php

use CodebarAg\Odoo\Responses\Session\DatabasesResponse;
use CodebarAg\Odoo\Responses\Session\HealthResponse;
use CodebarAg\Odoo\Responses\Session\VersionResponse;

it('health returns pass', function () {
    $response = HealthResponse::fromResponse($this->connector()->health());

    expect($response->successful())->toBeTrue()
        ->and($response->isHealthy())->toBeTrue();
})->group('live');

it('version returns server version', function () {
    $response = VersionResponse::fromResponse($this->connector()->version());
    
    expect($response->successful())->toBeTrue()
        ->and($response->serverVersion())->toBeString()->not->toBeEmpty();
})->group('live');

it('databases returns list', function () {
    $response = DatabasesResponse::fromResponse($this->connector()->databases());

    expect($response->successful())->toBeTrue()
        ->and($response->databases())->toBeArray();
})->group('live');
