<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Dto\Tasks;

use Spatie\LaravelData\Data;

class UpdateTaskDto extends Data
{
    /**
     * @param  array<int>  $userIds
     * @param  array<int>  $tagIds
     * @param  array<string, mixed>  $extraValues  Additional Odoo field values (e.g. custom studio fields)
     */
    public function __construct(
        public readonly int $id,
        public readonly ?string $name = null,
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
     * Serialise to the Odoo `write` value map. Only explicitly provided fields are written
     * and `extraValues` (studio fields) are merged at the top level.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $data = [];

        if ($this->name !== null) {
            $data['name'] = $this->name;
        }

        if ($this->projectId !== null) {
            $data['project_id'] = $this->projectId;
        }

        if ($this->description !== null) {
            $data['description'] = $this->description;
        }

        if ($this->userIds !== []) {
            $data['user_ids'] = [[6, 0, $this->userIds]];
        }

        if ($this->stageId !== null) {
            $data['stage_id'] = $this->stageId;
        }

        if ($this->dateDeadline !== null) {
            $data['date_deadline'] = $this->dateDeadline;
        }

        if ($this->priority !== null) {
            $data['priority'] = $this->priority;
        }

        if ($this->partnerId !== null) {
            $data['partner_id'] = $this->partnerId;
        }

        if ($this->companyId !== null) {
            $data['company_id'] = $this->companyId;
        }

        if ($this->parentId !== null) {
            $data['parent_id'] = $this->parentId;
        }

        if ($this->milestoneId !== null) {
            $data['milestone_id'] = $this->milestoneId;
        }

        if ($this->allocatedHours !== null) {
            $data['allocated_hours'] = $this->allocatedHours;
        }

        if ($this->tagIds !== []) {
            $data['tag_ids'] = [[6, 0, $this->tagIds]];
        }

        if ($this->active !== null) {
            $data['active'] = $this->active;
        }

        return [...$data, ...$this->extraValues];
    }
}
