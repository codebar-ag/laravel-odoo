<?php

it('health returns pass', function () {
    $response = $this->connector()->health();

    expect($response->successful())->toBeTrue()
        ->and($response->isHealthy())->toBeTrue();
})->group('live');

it('version returns server version', function () {
    $response = $this->connector()->version();

    expect($response->successful())->toBeTrue()
        ->and($response->serverVersion())->toBeString()->not->toBeEmpty();
})->group('live');

it('databases returns list', function () {
    $response = $this->connector()->databases();

    expect($response->successful())->toBeTrue()
        ->and($response->databases())->toBeArray();
})->group('live');
