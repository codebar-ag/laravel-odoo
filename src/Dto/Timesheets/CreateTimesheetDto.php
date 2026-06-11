<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Dto\Timesheets;

use Spatie\LaravelData\Data;

class CreateTimesheetDto extends Data
{
    /**
     * @param  array<string, mixed>  $extraValues  Additional Odoo field values (e.g. custom studio fields)
     */
    public function __construct(
        public readonly string $name,
        public readonly int $projectId,
        public readonly int $taskId,
        public readonly string $date,
        public readonly float $unitAmount,
        public readonly ?int $employeeId = null,
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
            'project_id' => $this->projectId,
            'task_id' => $this->taskId,
            'date' => $this->date,
            'unit_amount' => $this->unitAmount,
        ];

        if ($this->employeeId !== null) {
            $data['employee_id'] = $this->employeeId;
        }

        return [...$data, ...$this->extraValues];
    }
}
