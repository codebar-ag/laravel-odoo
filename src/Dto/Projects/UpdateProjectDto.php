<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Dto\Projects;

use Spatie\LaravelData\Data;

class UpdateProjectDto extends Data
{
    /**
     * @param  array<int>  $tagIds
     * @param  array<string, mixed>  $extraValues  Additional Odoo field values (e.g. custom studio fields)
     */
    public function __construct(
        public readonly int $id,
        public readonly ?string $name = null,
        public readonly ?string $description = null,
        public readonly ?int $partnerId = null,
        public readonly ?int $userId = null,
        public readonly ?string $dateStart = null,
        public readonly ?string $date = null,
        public readonly ?int $companyId = null,
        public readonly ?float $allocatedHours = null,
        public readonly ?string $privacyVisibility = null,
        public readonly array $tagIds = [],
        public readonly ?int $color = null,
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
            data_set($data, 'name', $this->name);
        }

        if ($this->description !== null) {
            data_set($data, 'description', $this->description);
        }

        if ($this->partnerId !== null) {
            data_set($data, 'partner_id', $this->partnerId);
        }

        if ($this->userId !== null) {
            data_set($data, 'user_id', $this->userId);
        }

        if ($this->dateStart !== null) {
            data_set($data, 'date_start', $this->dateStart);
        }

        if ($this->date !== null) {
            data_set($data, 'date', $this->date);
        }

        if ($this->companyId !== null) {
            data_set($data, 'company_id', $this->companyId);
        }

        if ($this->allocatedHours !== null) {
            data_set($data, 'allocated_hours', $this->allocatedHours);
        }

        if ($this->privacyVisibility !== null) {
            data_set($data, 'privacy_visibility', $this->privacyVisibility);
        }

        if ($this->tagIds !== []) {
            data_set($data, 'tag_ids', [[6, 0, $this->tagIds]]);
        }

        if ($this->color !== null) {
            data_set($data, 'color', $this->color);
        }

        if ($this->active !== null) {
            data_set($data, 'active', $this->active);
        }

        /** @var array<string, mixed> $data */
        return [...$data, ...$this->extraValues];
    }
}
