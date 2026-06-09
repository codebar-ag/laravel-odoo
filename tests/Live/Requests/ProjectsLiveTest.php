<?php

use CodebarAg\Odoo\Responses\Api\Projects\ProjectsResponse;

it('gets projects', function () {
    $response = ProjectsResponse::fromResponse(
        $this->connector()->getProjects(limit: 5)
    );

    expect($response->successful())->toBeTrue()
        ->and($response->body())->toBeJson();
})->group('live');
