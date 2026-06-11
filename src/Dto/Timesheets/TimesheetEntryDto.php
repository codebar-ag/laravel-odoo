<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Dto\Timesheets;

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

    /** @param array<array-key, mixed> $data */
    public static function fromArray(array $data): self
    {
        $projectId = data_get($data, 'project_id.0');
        $projectName = data_get($data, 'project_id.1');
        $taskId = data_get($data, 'task_id.0');
        $taskName = data_get($data, 'task_id.1');
        $employeeId = data_get($data, 'employee_id.0');
        $employeeName = data_get($data, 'employee_id.1');
        $unitAmountRaw = data_get($data, 'unit_amount', 0.0);

        return new self(
            id: \is_int($v = data_get($data, 'id', 0)) ? $v : 0,
            name: \is_string($v = data_get($data, 'name', '')) ? $v : '',
            projectId: \is_int($projectId) ? $projectId : null,
            projectName: \is_string($projectName) ? $projectName : null,
            taskId: \is_int($taskId) ? $taskId : null,
            taskName: \is_string($taskName) ? $taskName : null,
            unitAmount: \is_numeric($unitAmountRaw) ? \floatval($unitAmountRaw) : 0.0,
            date: \is_string($v = data_get($data, 'date', '')) ? $v : '',
            employeeId: \is_int($employeeId) ? $employeeId : null,
            employeeName: \is_string($employeeName) ? $employeeName : null,
        );
    }
}
