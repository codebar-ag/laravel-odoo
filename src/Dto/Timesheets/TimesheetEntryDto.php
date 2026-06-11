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
        public ?int $userId,
        public ?string $userName,
        public ?bool $validated,
        public ?string $validatedStatus,
        public ?bool $userCanValidate,
        public ?bool $readonlyTimesheet,
        public ?float $amount,
        public ?int $companyId,
        public ?string $companyName,
        public ?int $partnerId,
        public ?string $partnerName,
        public ?string $createDate,
        public ?string $writeDate,
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
        $userId = data_get($data, 'user_id.0');
        $userName = data_get($data, 'user_id.1');
        $companyId = data_get($data, 'company_id.0');
        $companyName = data_get($data, 'company_id.1');
        $partnerId = data_get($data, 'partner_id.0');
        $partnerName = data_get($data, 'partner_id.1');
        $unitAmountRaw = data_get($data, 'unit_amount', 0.0);
        $amountRaw = data_get($data, 'amount');
        $validated = data_get($data, 'validated');
        $userCanValidate = data_get($data, 'user_can_validate');
        $readonlyTimesheet = data_get($data, 'readonly_timesheet');

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
            userId: \is_int($userId) ? $userId : null,
            userName: \is_string($userName) ? $userName : null,
            validated: \is_bool($validated) ? $validated : null,
            validatedStatus: \is_string($v = data_get($data, 'validated_status')) ? $v : null,
            userCanValidate: \is_bool($userCanValidate) ? $userCanValidate : null,
            readonlyTimesheet: \is_bool($readonlyTimesheet) ? $readonlyTimesheet : null,
            amount: \is_numeric($amountRaw) ? \floatval($amountRaw) : null,
            companyId: \is_int($companyId) ? $companyId : null,
            companyName: \is_string($companyName) ? $companyName : null,
            partnerId: \is_int($partnerId) ? $partnerId : null,
            partnerName: \is_string($partnerName) ? $partnerName : null,
            createDate: \is_string($v = data_get($data, 'create_date')) ? $v : null,
            writeDate: \is_string($v = data_get($data, 'write_date')) ? $v : null,
        );
    }
}
