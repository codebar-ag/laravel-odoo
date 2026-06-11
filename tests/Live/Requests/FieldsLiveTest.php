<?php

it('gets all fields for account.analytic.line', function () {
    $response = $this->connector()->getAllFields();

    expect($response->successful())->toBeTrue()
        ->and($response->body())->toBeJson();
})->group('live');

it('gets fields for a given model', function () {
    $response = $this->connector()->getFields(model: 'project.project');

    expect($response->successful())->toBeTrue()
        ->and($response->body())->toBeJson();
})->group('live');
