<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Dto\CallKw\Timesheets;

use Illuminate\Support\Arr;

readonly class TimesheetEntryDto
{
    public function __construct(
        public int $id,
        public string $name,
        public ?int $projectId,
        public ?string $projectName,
        public ?int $taskId,
        public ?string $taskName,
        public float $unitAmount,
        public string $date,
        public ?int $employeeId,
        public ?string $employeeName,
    ) {}

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        $project = Arr::get($data, 'project_id');
        $task = Arr::get($data, 'task_id');
        $employee = Arr::get($data, 'employee_id');

        return new self(
            id: (int) Arr::get($data, 'id'),
            name: (string) Arr::get($data, 'name', ''),
            projectId: is_array($project) ? (int) $project[0] : null,
            projectName: is_array($project) ? (string) $project[1] : null,
            taskId: is_array($task) ? (int) $task[0] : null,
            taskName: is_array($task) ? (string) $task[1] : null,
            unitAmount: (float) Arr::get($data, 'unit_amount', 0.0),
            date: (string) Arr::get($data, 'date', ''),
            employeeId: is_array($employee) ? (int) $employee[0] : null,
            employeeName: is_array($employee) ? (string) $employee[1] : null,
        );
    }
}
