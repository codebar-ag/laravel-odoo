<?php

use CodebarAg\Odoo\Dto\Projects\ProjectDto;

it('maps odoo relation tuples and scalars', function () {
    $dto = ProjectDto::fromArray([
        'id' => 7,
        'name' => 'Internal',
        'description' => 'Internal work',
        'partner_id' => [3, 'ACME'],
        'user_id' => [5, 'Alice'],
        'date_start' => '2026-01-01',
        'date' => '2026-12-31',
    ]);

    expect($dto->id)->toBe(7)
        ->and($dto->name)->toBe('Internal')
        ->and($dto->description)->toBe('Internal work')
        ->and($dto->partnerId)->toBe(3)
        ->and($dto->partnerName)->toBe('ACME')
        ->and($dto->userId)->toBe(5)
        ->and($dto->userName)->toBe('Alice')
        ->and($dto->dateStart)->toBe('2026-01-01')
        ->and($dto->date)->toBe('2026-12-31');
});

it('falls back to safe defaults for missing or wrongly typed values', function () {
    $dto = ProjectDto::fromArray([
        'id' => 'not-an-int',
        'partner_id' => false,
        'user_id' => false,
    ]);

    expect($dto->id)->toBe(0)
        ->and($dto->name)->toBe('')
        ->and($dto->description)->toBeNull()
        ->and($dto->partnerId)->toBeNull()
        ->and($dto->partnerName)->toBeNull()
        ->and($dto->userId)->toBeNull()
        ->and($dto->date)->toBeNull();
});

it('maps the additional detail fields', function () {
    $dto = ProjectDto::fromArray([
        'id' => 1,
        'name' => 'Internal',
        'active' => true,
        'company_id' => [1, 'MyCompany'],
        'allocated_hours' => 0.0,
        'effective_hours' => 87.25,
        'remaining_hours' => -87.25,
        'task_count' => 2,
        'last_update_status' => 'to_define',
        'privacy_visibility' => 'portal',
        'tag_ids' => [3, 'bad', 7],
        'color' => 0,
        'create_date' => '2026-06-09 09:18:06',
        'write_date' => '2026-06-09 09:18:06',
    ]);

    expect($dto->active)->toBeTrue()
        ->and($dto->companyId)->toBe(1)
        ->and($dto->companyName)->toBe('MyCompany')
        ->and($dto->allocatedHours)->toBe(0.0)
        ->and($dto->effectiveHours)->toBe(87.25)
        ->and($dto->remainingHours)->toBe(-87.25)
        ->and($dto->taskCount)->toBe(2)
        ->and($dto->lastUpdateStatus)->toBe('to_define')
        ->and($dto->privacyVisibility)->toBe('portal')
        ->and($dto->tagIds)->toBe([3, 7])
        ->and($dto->color)->toBe(0)
        ->and($dto->createDate)->toBe('2026-06-09 09:18:06')
        ->and($dto->writeDate)->toBe('2026-06-09 09:18:06');
});

it('leaves additional fields null/empty when absent', function () {
    $dto = ProjectDto::fromArray(['id' => 1, 'name' => 'Internal']);

    expect($dto->active)->toBeNull()
        ->and($dto->companyId)->toBeNull()
        ->and($dto->allocatedHours)->toBeNull()
        ->and($dto->taskCount)->toBeNull()
        ->and($dto->lastUpdateStatus)->toBeNull()
        ->and($dto->tagIds)->toBe([])
        ->and($dto->color)->toBeNull()
        ->and($dto->createDate)->toBeNull();
});
