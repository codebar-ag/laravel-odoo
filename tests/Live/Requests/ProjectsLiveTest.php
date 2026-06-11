<?php

it('gets projects', function () {
    $response = $this->connector()->getProjects(limit: 5);

    expect($response->successful())->toBeTrue()
        ->and($response->body())->toBeJson();
})->group('live');
