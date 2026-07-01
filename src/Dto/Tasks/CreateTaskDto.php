<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Dto\Tasks;

use Spatie\LaravelData\Data;

class CreateTaskDto extends Data
{
    /**
     * @param  array<int>  $userIds
     * @param  array<int>  $tagIds
     * @param  array<string, mixed>  $extraValues  Additional Odoo field values (e.g. custom studio fields)
     */
    public function __construct(
        public readonly string $name,
        public readonly ?int $projectId = null,
        public readonly ?string $description = null,
        public readonly array $userIds = [],
        public readonly ?int $stageId = null,
        public readonly ?string $dateDeadline = null,
        public readonly ?string $priority = null,
        public readonly ?int $partnerId = null,
        public readonly ?int $companyId = null,
        public readonly ?int $parentId = null,
        public readonly ?int $milestoneId = null,
        public readonly ?float $allocatedHours = null,
        public readonly array $tagIds = [],
        public readonly ?bool $active = null,
        public readonly array $extraValues = [],
    ) {}

    /**
     * Serialise to the Odoo `create` value map. Optional fields are omitted when null
     * and `extraValues` (studio fields) are merged at the top level.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $data = [
            'name' => $this->name,
        ];

        if ($this->projectId !== null) {
            data_set($data, 'project_id', $this->projectId);
        }

        if ($this->description !== null) {
            data_set($data, 'description', $this->description);
        }

        if ($this->userIds !== []) {
            data_set($data, 'user_ids', [[6, 0, $this->userIds]]);
        }

        if ($this->stageId !== null) {
            data_set($data, 'stage_id', $this->stageId);
        }

        if ($this->dateDeadline !== null) {
            data_set($data, 'date_deadline', $this->dateDeadline);
        }

        if ($this->priority !== null) {
            data_set($data, 'priority', $this->priority);
        }

        if ($this->partnerId !== null) {
            data_set($data, 'partner_id', $this->partnerId);
        }

        if ($this->companyId !== null) {
            data_set($data, 'company_id', $this->companyId);
        }

        if ($this->parentId !== null) {
            data_set($data, 'parent_id', $this->parentId);
        }

        if ($this->milestoneId !== null) {
            data_set($data, 'milestone_id', $this->milestoneId);
        }

        if ($this->allocatedHours !== null) {
            data_set($data, 'allocated_hours', $this->allocatedHours);
        }

        if ($this->tagIds !== []) {
            data_set($data, 'tag_ids', [[6, 0, $this->tagIds]]);
        }

        if ($this->active !== null) {
            data_set($data, 'active', $this->active);
        }

        return [...$data, ...$this->extraValues];
    }
}
