<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Dto\Tasks;

readonly class TaskDto
{
    /**
     * @param array<int> $userIds
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
    ) {
    }

    /** @param array<array-key, mixed> $data */
    public static function fromArray(array $data): self
    {
        $project = $data['project_id'] ?? false;
        $stage = $data['stage_id'] ?? false;
        $rawUserIds = $data['user_ids'] ?? [];

        $projectId = \is_array($project) ? ($project[0] ?? null) : null;
        $projectName = \is_array($project) ? ($project[1] ?? null) : null;
        $stageId = \is_array($stage) ? ($stage[0] ?? null) : null;
        $stageName = \is_array($stage) ? ($stage[1] ?? null) : null;

        return new self(
            id: \is_int($v = $data['id'] ?? 0) ? $v : 0,
            name: \is_string($v = $data['name'] ?? '') ? $v : '',
            description: \is_string($v = $data['description'] ?? null) ? $v : null,
            projectId: \is_int($projectId) ? $projectId : null,
            projectName: \is_string($projectName) ? $projectName : null,
            userIds: \is_array($rawUserIds) ? \array_values(\array_filter($rawUserIds, '\is_int')) : [],
            stageId: \is_int($stageId) ? $stageId : null,
            stageName: \is_string($stageName) ? $stageName : null,
            dateDeadline: \is_string($v = $data['date_deadline'] ?? null) ? $v : null,
            priority: \is_string($v = $data['priority'] ?? '0') ? $v : '0',
        );
    }
}
