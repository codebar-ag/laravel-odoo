<?php

use CodebarAg\Odoo\Dto\Tasks\CreateTaskDto;
use CodebarAg\Odoo\Dto\Tasks\UpdateTaskDto;
use CodebarAg\Odoo\Responses\Api\Tasks\CreateTaskResponse;
use CodebarAg\Odoo\Responses\Api\Tasks\MutateTaskResponse;

it('gets all tasks', function () {
    $response = $this->connector()->getAllTasks(limit: 5);

    expect($response->successful())->toBeTrue()
        ->and($response->body())->toBeJson();
})->group('live');

it('gets tasks by project', function () {
    $projects = $this->connector()->getProjects(limit: 1);
    $projectDtos = $projects->projects();

    if (blank($projectDtos)) {
        $this->markTestSkipped('No projects found');
    }

    $response = $this->connector()->getTasksByProject(projectId: data_get($projectDtos, '0.id'));

    expect($response->successful())->toBeTrue()
        ->and($response->body())->toBeJson();
})->group('live');

it('creates a task and returns its id', function () {
    $response = $this->connector()->createTask(new CreateTaskDto(
        name: 'Test Task',
    ));

    expect($response)->toBeInstanceOf(CreateTaskResponse::class);
    expect($response->id())->toBeInt();
})->group('live');

it('updates a task', function () {
    $createResponse = $this->connector()->createTask(new CreateTaskDto(
        name: 'Test Task',
    ));

    expect($createResponse->id())->toBeInt();

    $updateResponse = $this->connector()->updateTask(new UpdateTaskDto(
        id: $createResponse->id(),
        name: 'Test Task Updated',
    ));

    expect($updateResponse)->toBeInstanceOf(MutateTaskResponse::class);
    expect($updateResponse->ok())->toBeTrue();
})->group('live');

it('creates and deletes a task', function () {
    $createResponse = $this->connector()->createTask(new CreateTaskDto(
        name: 'Test Task Delete',
    ));

    expect($createResponse->id())->toBeInt();

    $deleteResponse = $this->connector()->deleteTask($createResponse->id());

    expect($deleteResponse)->toBeInstanceOf(MutateTaskResponse::class);
    expect($deleteResponse->ok())->toBeTrue();
})->group('live');
