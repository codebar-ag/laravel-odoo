<?php

use CodebarAg\Odoo\Dto\Projects\CreateProjectDto;
use CodebarAg\Odoo\Dto\Projects\UpdateProjectDto;
use CodebarAg\Odoo\Responses\Api\Projects\CreateProjectResponse;
use CodebarAg\Odoo\Responses\Api\Projects\MutateProjectResponse;

it('gets projects', function () {
    $response = $this->connector()->getProjects(limit: 5);

    expect($response->successful())->toBeTrue()
        ->and($response->body())->toBeJson();
})->group('live');

it('creates a project and returns its id', function () {
    $response = $this->connector()->createProject(new CreateProjectDto(
        name: 'Test Project',
    ));

    expect($response)->toBeInstanceOf(CreateProjectResponse::class);
    expect($response->id())->toBeInt();
})->group('live');

it('updates a project', function () {
    $createResponse = $this->connector()->createProject(new CreateProjectDto(
        name: 'Test Project',
    ));

    expect($createResponse->id())->toBeInt();

    $updateResponse = $this->connector()->updateProject(new UpdateProjectDto(
        id: $createResponse->id(),
        name: 'Test Project Updated',
    ));

    expect($updateResponse)->toBeInstanceOf(MutateProjectResponse::class);
    expect($updateResponse->ok())->toBeTrue();
})->group('live');

it('creates and deletes a project', function () {
    $createResponse = $this->connector()->createProject(new CreateProjectDto(
        name: 'Test Project Delete',
    ));

    expect($createResponse->id())->toBeInt();

    $deleteResponse = $this->connector()->deleteProject($createResponse->id());

    expect($deleteResponse)->toBeInstanceOf(MutateProjectResponse::class);
    expect($deleteResponse->ok())->toBeTrue();
})->group('live');
