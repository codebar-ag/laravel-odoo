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
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        $project = Arr::get($data, 'project_id');
        $task = Arr::get($data, 'task_id');
        $employee = Arr::get($data, 'employee_id');

        return new self(
            id: \intval(Arr::get($data, 'id')),
            name: \strval(Arr::get($data, 'name') ?? ''),
            projectId: \is_array($project) ? \intval($project[0]) : null,
            projectName: \is_array($project) ? \strval($project[1] ?? '') : null,
            taskId: \is_array($task) ? \intval($task[0]) : null,
            taskName: \is_array($task) ? \strval($task[1] ?? '') : null,
            unitAmount: \floatval(Arr::get($data, 'unit_amount') ?? 0.0),
            date: \strval(Arr::get($data, 'date') ?? ''),
            employeeId: \is_array($employee) ? \intval($employee[0]) : null,
            employeeName: \is_array($employee) ? \strval($employee[1] ?? '') : null,
        );
    }
}
