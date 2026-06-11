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
