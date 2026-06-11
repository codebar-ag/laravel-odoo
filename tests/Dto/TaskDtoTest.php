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

it('maps the additional detail fields', function () {
    $dto = TaskDto::fromArray([
        'id' => 2,
        'name' => 'Meeting',
        'active' => true,
        'state' => '01_in_progress',
        'partner_id' => [4, 'ACME'],
        'company_id' => [1, 'MyCompany'],
        'parent_id' => [9, 'Parent task'],
        'milestone_id' => [5, 'Phase 1'],
        'allocated_hours' => 8.0,
        'effective_hours' => 3.5,
        'remaining_hours' => 4.5,
        'total_hours_spent' => 3.5,
        'progress' => 43.75,
        'subtask_count' => 2,
        'child_ids' => [10, 'bad', 11],
        'tag_ids' => [3, 7],
        'date_assign' => '2026-06-09 09:20:00',
        'date_last_stage_update' => '2026-06-09 09:25:00',
        'create_date' => '2026-06-09 09:18:06',
        'write_date' => '2026-06-09 09:18:06',
    ]);

    expect($dto->active)->toBeTrue()
        ->and($dto->state)->toBe('01_in_progress')
        ->and($dto->partnerId)->toBe(4)
        ->and($dto->partnerName)->toBe('ACME')
        ->and($dto->companyId)->toBe(1)
        ->and($dto->companyName)->toBe('MyCompany')
        ->and($dto->parentId)->toBe(9)
        ->and($dto->parentName)->toBe('Parent task')
        ->and($dto->milestoneId)->toBe(5)
        ->and($dto->milestoneName)->toBe('Phase 1')
        ->and($dto->allocatedHours)->toBe(8.0)
        ->and($dto->effectiveHours)->toBe(3.5)
        ->and($dto->remainingHours)->toBe(4.5)
        ->and($dto->totalHoursSpent)->toBe(3.5)
        ->and($dto->progress)->toBe(43.75)
        ->and($dto->subtaskCount)->toBe(2)
        ->and($dto->childIds)->toBe([10, 11])
        ->and($dto->tagIds)->toBe([3, 7])
        ->and($dto->dateAssign)->toBe('2026-06-09 09:20:00')
        ->and($dto->dateLastStageUpdate)->toBe('2026-06-09 09:25:00')
        ->and($dto->createDate)->toBe('2026-06-09 09:18:06')
        ->and($dto->writeDate)->toBe('2026-06-09 09:18:06');
});

it('leaves additional fields null/empty when absent', function () {
    $dto = TaskDto::fromArray(['id' => 1, 'name' => 'Task']);

    expect($dto->active)->toBeNull()
        ->and($dto->state)->toBeNull()
        ->and($dto->partnerId)->toBeNull()
        ->and($dto->companyId)->toBeNull()
        ->and($dto->parentId)->toBeNull()
        ->and($dto->milestoneId)->toBeNull()
        ->and($dto->allocatedHours)->toBeNull()
        ->and($dto->progress)->toBeNull()
        ->and($dto->subtaskCount)->toBeNull()
        ->and($dto->childIds)->toBe([])
        ->and($dto->tagIds)->toBe([])
        ->and($dto->dateAssign)->toBeNull()
        ->and($dto->createDate)->toBeNull();
});
