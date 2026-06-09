<?php

use CodebarAg\Odoo\Responses\Api\Tasks\TasksResponse;
it('gets all tasks', function () {
    $response = TasksResponse::fromResponse(
        $this->connector()->getAllTasks(limit: 5)
    );

    expect($response->successful())->toBeTrue()
        ->and($response->body())->toBeJson();
})->group('live');

it('gets tasks by project', function () {
    $projects = $this->connector()->getProjects(limit: 1);
    $body = $projects->json();

    if (empty($body)) {
        $this->markTestSkipped('No projects found');
    }

    $projectId = $body[0]['id'];

    $response = TasksResponse::fromResponse(
        $this->connector()->getTasksByProject(projectId: $projectId)
    );

    expect($response->successful())->toBeTrue()
        ->and($response->body())->toBeJson();
})->group('live');
