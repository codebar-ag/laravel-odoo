<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Dto\Timesheets;

use Spatie\LaravelData\Data;

class UpdateTimesheetDto extends Data
{
    /**
     * @param  array<string, mixed>  $extraValues  Additional Odoo field values (e.g. custom studio fields)
     */
    public function __construct(
        public readonly int $id,
        public readonly ?string $name = null,
        public readonly ?int $projectId = null,
        public readonly ?int $taskId = null,
        public readonly ?string $date = null,
        public readonly ?float $unitAmount = null,
        public readonly ?int $employeeId = null,
        public readonly array $extraValues = [],
    ) {}

    /**
     * Serialise to the Odoo `write` value map. The record `id` is carried separately by
     * the request; only the fields explicitly provided are written, and `extraValues`
     * (studio fields) are merged at the top level.
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

        if ($this->taskId !== null) {
            $data['task_id'] = $this->taskId;
        }

        if ($this->date !== null) {
            $data['date'] = $this->date;
        }

        if ($this->unitAmount !== null) {
            $data['unit_amount'] = $this->unitAmount;
        }

        if ($this->employeeId !== null) {
            $data['employee_id'] = $this->employeeId;
        }

        return array_merge($data, $this->extraValues);
    }
}
