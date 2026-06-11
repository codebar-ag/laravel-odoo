<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Dto\Tasks;

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

    /** @param array<array-key, mixed> $data */
    public static function fromArray(array $data): self
    {
        $rawUserIds = data_get($data, 'user_ids', []);

        $projectId = data_get($data, 'project_id.0');
        $projectName = data_get($data, 'project_id.1');
        $stageId = data_get($data, 'stage_id.0');
        $stageName = data_get($data, 'stage_id.1');

        return new self(
            id: \is_int($v = data_get($data, 'id', 0)) ? $v : 0,
            name: \is_string($v = data_get($data, 'name', '')) ? $v : '',
            description: \is_string($v = data_get($data, 'description')) ? $v : null,
            projectId: \is_int($projectId) ? $projectId : null,
            projectName: \is_string($projectName) ? $projectName : null,
            userIds: collect(\is_array($rawUserIds) ? $rawUserIds : [])
                ->filter(fn ($id) => \is_int($id))
                ->values()
                ->all(),
            stageId: \is_int($stageId) ? $stageId : null,
            stageName: \is_string($stageName) ? $stageName : null,
            dateDeadline: \is_string($v = data_get($data, 'date_deadline')) ? $v : null,
            priority: \is_string($v = data_get($data, 'priority', '0')) ? $v : '0',
        );
    }
}
