<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Dto\CallKw\Timesheets;

readonly class CreateTimesheetDto
{
    public function __construct(
        public string $name,
        public int $projectId,
        public int $taskId,
        public string $date,
        public float $unitAmount,
    ) {
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'project_id' => $this->projectId,
            'task_id' => $this->taskId,
            'date' => $this->date,
            'unit_amount' => $this->unitAmount,
        ];
    }
}
