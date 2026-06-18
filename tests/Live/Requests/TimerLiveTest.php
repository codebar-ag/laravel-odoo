<?php

declare(strict_types=1);

it('running timers returns an array', function () {
    $response = $this->connector()->getRunningTimers();

    expect($response->successful())->toBeTrue()
        ->and($response->timers())->toBeArray();
})->group('live');
