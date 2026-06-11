<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Dto\Projects;

readonly class ProjectDto
{
    /**
     * @param  array<int>  $tagIds
     */
    public function __construct(
        public int $id,
        public string $name,
        public ?string $description,
        public ?int $partnerId,
        public ?string $partnerName,
        public ?int $userId,
        public ?string $userName,
        public ?string $dateStart,
        public ?string $date,
        public ?bool $active,
        public ?int $companyId,
        public ?string $companyName,
        public ?float $allocatedHours,
        public ?float $effectiveHours,
        public ?float $remainingHours,
        public ?int $taskCount,
        public ?string $lastUpdateStatus,
        public ?string $privacyVisibility,
        public array $tagIds,
        public ?int $color,
        public ?string $createDate,
        public ?string $writeDate,
    ) {}

    /** @param array<array-key, mixed> $data */
    public static function fromArray(array $data): self
    {
        $partnerId = data_get($data, 'partner_id.0');
        $partnerName = data_get($data, 'partner_id.1');
        $userId = data_get($data, 'user_id.0');
        $userName = data_get($data, 'user_id.1');
        $companyId = data_get($data, 'company_id.0');
        $companyName = data_get($data, 'company_id.1');
        $active = data_get($data, 'active');
        $allocatedHours = data_get($data, 'allocated_hours');
        $effectiveHours = data_get($data, 'effective_hours');
        $remainingHours = data_get($data, 'remaining_hours');
        $taskCount = data_get($data, 'task_count');
        $color = data_get($data, 'color');
        $rawTagIds = data_get($data, 'tag_ids', []);

        return new self(
            id: \is_int($v = data_get($data, 'id', 0)) ? $v : 0,
            name: \is_string($v = data_get($data, 'name', '')) ? $v : '',
            description: \is_string($v = data_get($data, 'description')) ? $v : null,
            partnerId: \is_int($partnerId) ? $partnerId : null,
            partnerName: \is_string($partnerName) ? $partnerName : null,
            userId: \is_int($userId) ? $userId : null,
            userName: \is_string($userName) ? $userName : null,
            dateStart: \is_string($v = data_get($data, 'date_start')) ? $v : null,
            date: \is_string($v = data_get($data, 'date')) ? $v : null,
            active: \is_bool($active) ? $active : null,
            companyId: \is_int($companyId) ? $companyId : null,
            companyName: \is_string($companyName) ? $companyName : null,
            allocatedHours: \is_numeric($allocatedHours) ? \floatval($allocatedHours) : null,
            effectiveHours: \is_numeric($effectiveHours) ? \floatval($effectiveHours) : null,
            remainingHours: \is_numeric($remainingHours) ? \floatval($remainingHours) : null,
            taskCount: \is_int($taskCount) ? $taskCount : null,
            lastUpdateStatus: \is_string($v = data_get($data, 'last_update_status')) ? $v : null,
            privacyVisibility: \is_string($v = data_get($data, 'privacy_visibility')) ? $v : null,
            tagIds: collect(\is_array($rawTagIds) ? $rawTagIds : [])
                ->filter(fn ($id) => \is_int($id))
                ->values()
                ->all(),
            color: \is_int($color) ? $color : null,
            createDate: \is_string($v = data_get($data, 'create_date')) ? $v : null,
            writeDate: \is_string($v = data_get($data, 'write_date')) ? $v : null,
        );
    }
}
