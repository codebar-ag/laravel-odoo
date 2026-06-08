<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Dto\CallKw\Tasks;

use Illuminate\Support\Arr;

readonly class TaskDto
{
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
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        $project = Arr::get($data, 'project_id');
        $stage = Arr::get($data, 'stage_id');

        return new self(
            id: (int) Arr::get($data, 'id'),
            name: (string) Arr::get($data, 'name'),
            description: ($v = Arr::get($data, 'description')) ? (string) $v : null,
            projectId: is_array($project) ? (int) $project[0] : null,
            projectName: is_array($project) ? (string) $project[1] : null,
            userIds: (array) Arr::get($data, 'user_ids', []),
            stageId: is_array($stage) ? (int) $stage[0] : null,
            stageName: is_array($stage) ? (string) $stage[1] : null,
            dateDeadline: ($v = Arr::get($data, 'date_deadline')) ? (string) $v : null,
            priority: (string) Arr::get($data, 'priority', '0'),
        );
    }
}
