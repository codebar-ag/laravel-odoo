<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Dto\Tasks;

readonly class TaskDto
{
    /**
     * @param  array<int>  $userIds
     * @param  array<int>  $childIds
     * @param  array<int>  $tagIds
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
        public ?bool $active,
        public ?string $state,
        public ?int $partnerId,
        public ?string $partnerName,
        public ?int $companyId,
        public ?string $companyName,
        public ?int $parentId,
        public ?string $parentName,
        public ?int $milestoneId,
        public ?string $milestoneName,
        public ?float $allocatedHours,
        public ?float $effectiveHours,
        public ?float $remainingHours,
        public ?float $totalHoursSpent,
        public ?float $progress,
        public ?int $subtaskCount,
        public array $childIds,
        public array $tagIds,
        public ?string $dateAssign,
        public ?string $dateLastStageUpdate,
        public ?string $createDate,
        public ?string $writeDate,
    ) {}

    /** @param array<array-key, mixed> $data */
    public static function fromArray(array $data): self
    {
        $rawUserIds = data_get($data, 'user_ids', []);
        $rawChildIds = data_get($data, 'child_ids', []);
        $rawTagIds = data_get($data, 'tag_ids', []);

        $projectId = data_get($data, 'project_id.0');
        $projectName = data_get($data, 'project_id.1');
        $stageId = data_get($data, 'stage_id.0');
        $stageName = data_get($data, 'stage_id.1');
        $partnerId = data_get($data, 'partner_id.0');
        $partnerName = data_get($data, 'partner_id.1');
        $companyId = data_get($data, 'company_id.0');
        $companyName = data_get($data, 'company_id.1');
        $parentId = data_get($data, 'parent_id.0');
        $parentName = data_get($data, 'parent_id.1');
        $milestoneId = data_get($data, 'milestone_id.0');
        $milestoneName = data_get($data, 'milestone_id.1');

        $active = data_get($data, 'active');
        $allocatedHours = data_get($data, 'allocated_hours');
        $effectiveHours = data_get($data, 'effective_hours');
        $remainingHours = data_get($data, 'remaining_hours');
        $totalHoursSpent = data_get($data, 'total_hours_spent');
        $progress = data_get($data, 'progress');
        $subtaskCount = data_get($data, 'subtask_count');

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
            active: \is_bool($active) ? $active : null,
            state: \is_string($v = data_get($data, 'state')) ? $v : null,
            partnerId: \is_int($partnerId) ? $partnerId : null,
            partnerName: \is_string($partnerName) ? $partnerName : null,
            companyId: \is_int($companyId) ? $companyId : null,
            companyName: \is_string($companyName) ? $companyName : null,
            parentId: \is_int($parentId) ? $parentId : null,
            parentName: \is_string($parentName) ? $parentName : null,
            milestoneId: \is_int($milestoneId) ? $milestoneId : null,
            milestoneName: \is_string($milestoneName) ? $milestoneName : null,
            allocatedHours: \is_numeric($allocatedHours) ? \floatval($allocatedHours) : null,
            effectiveHours: \is_numeric($effectiveHours) ? \floatval($effectiveHours) : null,
            remainingHours: \is_numeric($remainingHours) ? \floatval($remainingHours) : null,
            totalHoursSpent: \is_numeric($totalHoursSpent) ? \floatval($totalHoursSpent) : null,
            progress: \is_numeric($progress) ? \floatval($progress) : null,
            subtaskCount: \is_int($subtaskCount) ? $subtaskCount : null,
            childIds: collect(\is_array($rawChildIds) ? $rawChildIds : [])
                ->filter(fn ($id) => \is_int($id))
                ->values()
                ->all(),
            tagIds: collect(\is_array($rawTagIds) ? $rawTagIds : [])
                ->filter(fn ($id) => \is_int($id))
                ->values()
                ->all(),
            dateAssign: \is_string($v = data_get($data, 'date_assign')) ? $v : null,
            dateLastStageUpdate: \is_string($v = data_get($data, 'date_last_stage_update')) ? $v : null,
            createDate: \is_string($v = data_get($data, 'create_date')) ? $v : null,
            writeDate: \is_string($v = data_get($data, 'write_date')) ? $v : null,
        );
    }
}
