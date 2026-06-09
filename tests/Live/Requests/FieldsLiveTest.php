<?php

use CodebarAg\Odoo\Responses\Api\Fields\FieldsResponse;

it('gets all fields for account.analytic.line', function () {
    $response = FieldsResponse::fromResponse(
        $this->connector()->getAllFields()
    );

    expect($response->successful())->toBeTrue()
        ->and($response->body())->toBeJson();
})->group('live');

it('gets fields for a given model', function () {
    $response = FieldsResponse::fromResponse(
        $this->connector()->getFields(model: 'project.project')
    );

    expect($response->successful())->toBeTrue()
        ->and($response->body())->toBeJson();
})->group('live');
