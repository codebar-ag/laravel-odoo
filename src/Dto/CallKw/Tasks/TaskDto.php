<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Dto\CallKw\Tasks;

use Illuminate\Support\Arr;

readonly class TaskDto
{
    /**
     * @param  array<int>  $userIds
     */
    public function __construct(
        public int $id,
        public string $name,
        public ?string $description,
        public ?int $projectId,
        public ?string $projectName,
        public array $userIds,
        public ?int $stageId,
        public ?string $stageName,
        public ?string $dateDeadline,
        public string $priority,
    ) {}

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        $project = Arr::get($data, 'project_id');
        $stage = Arr::get($data, 'stage_id');
        $rawUserIds = Arr::get($data, 'user_ids', []);

        return new self(
            id: \intval(Arr::get($data, 'id')),
            name: \strval(Arr::get($data, 'name') ?? ''),
            description: ($v = Arr::get($data, 'description')) ? \strval($v) : null,
            projectId: \is_array($project) ? \intval($project[0]) : null,
            projectName: \is_array($project) ? \strval($project[1] ?? '') : null,
            userIds: \is_array($rawUserIds) ? array_map(\intval(...), $rawUserIds) : [],
            stageId: \is_array($stage) ? \intval($stage[0]) : null,
            stageName: \is_array($stage) ? \strval($stage[1] ?? '') : null,
            dateDeadline: ($v = Arr::get($data, 'date_deadline')) ? \strval($v) : null,
            priority: \strval(Arr::get($data, 'priority') ?? '0'),
        );
    }
}
