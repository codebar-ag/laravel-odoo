<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Dto\Timesheets;

readonly class CreateTimesheetDto
{
    /**
     * @param  array<string, mixed>  $extraValues  Additional Odoo field values (e.g. custom studio fields)
     */
    public function __construct(
        public string $name,
        public int $projectId,
        public int $taskId,
        public string $date,
        public float $unitAmount,
        public ?int $employeeId = null,
        public array $extraValues = [],
    ) {}

    /** @return array<string, mixed> */
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
