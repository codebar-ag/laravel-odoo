<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Dto\Timesheets;

class TimesheetEntryDto
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

    /** @param array<array-key, mixed> $data */
    public static function fromArray(array $data): self
    {
        $project = $data['project_id'] ?? false;
        $task = $data['task_id'] ?? false;
        $employee = $data['employee_id'] ?? false;

        $projectId = \is_array($project) ? ($project[0] ?? null) : null;
        $projectName = \is_array($project) ? ($project[1] ?? null) : null;
        $taskId = \is_array($task) ? ($task[0] ?? null) : null;
        $taskName = \is_array($task) ? ($task[1] ?? null) : null;
        $employeeId = \is_array($employee) ? ($employee[0] ?? null) : null;
        $employeeName = \is_array($employee) ? ($employee[1] ?? null) : null;
        $unitAmountRaw = $data['unit_amount'] ?? 0.0;

        return new self(
            id: \is_int($v = $data['id'] ?? 0) ? $v : 0,
            name: \is_string($v = $data['name'] ?? '') ? $v : '',
            projectId: \is_int($projectId) ? $projectId : null,
            projectName: \is_string($projectName) ? $projectName : null,
            taskId: \is_int($taskId) ? $taskId : null,
            taskName: \is_string($taskName) ? $taskName : null,
            unitAmount: \is_numeric($unitAmountRaw) ? \floatval($unitAmountRaw) : 0.0,
            date: \is_string($v = $data['date'] ?? '') ? $v : '',
            employeeId: \is_int($employeeId) ? $employeeId : null,
            employeeName: \is_string($employeeName) ? $employeeName : null,
        );
    }
}
