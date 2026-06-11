<?php

use CodebarAg\Odoo\Dto\Tasks\TaskDto;

it('maps relation tuples and filters user ids to ints', function () {
    $dto = TaskDto::fromArray([
        'id' => 2,
        'name' => 'Meeting',
        'description' => 'Weekly sync',
        'project_id' => [1, 'Internal'],
        'user_ids' => [4, 'oops', 9],
        'stage_id' => [3, 'In Progress'],
        'date_deadline' => '2026-06-30',
        'priority' => '1',
    ]);

    expect($dto->id)->toBe(2)
        ->and($dto->name)->toBe('Meeting')
        ->and($dto->projectId)->toBe(1)
        ->and($dto->projectName)->toBe('Internal')
        ->and($dto->userIds)->toBe([4, 9])
        ->and($dto->stageId)->toBe(3)
        ->and($dto->stageName)->toBe('In Progress')
        ->and($dto->dateDeadline)->toBe('2026-06-30')
        ->and($dto->priority)->toBe('1');
});

it('defaults priority and user ids when absent', function () {
    $dto = TaskDto::fromArray(['id' => 1, 'name' => 'Task']);

    expect($dto->userIds)->toBe([])
        ->and($dto->priority)->toBe('0')
        ->and($dto->projectId)->toBeNull();
});
