<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Dto\Timesheets;

readonly class UpdateTimesheetDto
{
    /**
     * @param  array<string, mixed>  $extraValues  Additional Odoo field values (e.g. custom studio fields)
     */
    public function __construct(
        public int $id,
        public ?string $name = null,
        public ?int $projectId = null,
        public ?int $taskId = null,
        public ?string $date = null,
        public ?float $unitAmount = null,
        public ?int $employeeId = null,
        public array $extraValues = [],
    ) {}

    /** @return array<string, mixed> */
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
