<?php

it('gets all tasks', function () {
    $response = $this->connector()->getAllTasks(limit: 5);

    expect($response->successful())->toBeTrue()
        ->and($response->body())->toBeJson();
})->group('live');

it('gets tasks by project', function () {
    $projects = $this->connector()->getProjects(limit: 1);
    $projectDtos = $projects->projects();

    if (empty($projectDtos)) {
        $this->markTestSkipped('No projects found');
    }

    $response = $this->connector()->getTasksByProject(projectId: $projectDtos[0]->id);

    expect($response->successful())->toBeTrue()
        ->and($response->body())->toBeJson();
})->group('live');
