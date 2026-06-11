<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Dto\Employees;

readonly class EmployeeDto
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $workEmail,
        public ?string $workPhone,
        public ?string $mobilePhone,
        public ?string $jobTitle,
        public ?int $departmentId,
        public ?string $departmentName,
        public ?int $jobId,
        public ?string $jobName,
        public ?int $parentId,
        public ?string $parentName,
        public ?int $coachId,
        public ?string $coachName,
        public ?int $userId,
        public ?string $userName,
        public ?int $companyId,
        public ?string $companyName,
        public ?int $timesheetManagerId,
        public ?string $timesheetManagerName,
        public ?string $lastValidatedTimesheetDate,
        public ?bool $active,
    ) {}

    /** @param array<array-key, mixed> $data */
    public static function fromArray(array $data): self
    {
        $departmentId = data_get($data, 'department_id.0');
        $departmentName = data_get($data, 'department_id.1');
        $jobId = data_get($data, 'job_id.0');
        $jobName = data_get($data, 'job_id.1');
        $parentId = data_get($data, 'parent_id.0');
        $parentName = data_get($data, 'parent_id.1');
        $coachId = data_get($data, 'coach_id.0');
        $coachName = data_get($data, 'coach_id.1');
        $userId = data_get($data, 'user_id.0');
        $userName = data_get($data, 'user_id.1');
        $companyId = data_get($data, 'company_id.0');
        $companyName = data_get($data, 'company_id.1');
        $timesheetManagerId = data_get($data, 'timesheet_manager_id.0');
        $timesheetManagerName = data_get($data, 'timesheet_manager_id.1');
        $active = data_get($data, 'active');

        return new self(
            id: \is_int($v = data_get($data, 'id', 0)) ? $v : 0,
            name: \is_string($v = data_get($data, 'name', '')) ? $v : '',
            workEmail: \is_string($v = data_get($data, 'work_email')) ? $v : null,
            workPhone: \is_string($v = data_get($data, 'work_phone')) ? $v : null,
            mobilePhone: \is_string($v = data_get($data, 'mobile_phone')) ? $v : null,
            jobTitle: \is_string($v = data_get($data, 'job_title')) ? $v : null,
            departmentId: \is_int($departmentId) ? $departmentId : null,
            departmentName: \is_string($departmentName) ? $departmentName : null,
            jobId: \is_int($jobId) ? $jobId : null,
            jobName: \is_string($jobName) ? $jobName : null,
            parentId: \is_int($parentId) ? $parentId : null,
            parentName: \is_string($parentName) ? $parentName : null,
            coachId: \is_int($coachId) ? $coachId : null,
            coachName: \is_string($coachName) ? $coachName : null,
            userId: \is_int($userId) ? $userId : null,
            userName: \is_string($userName) ? $userName : null,
            companyId: \is_int($companyId) ? $companyId : null,
            companyName: \is_string($companyName) ? $companyName : null,
            timesheetManagerId: \is_int($timesheetManagerId) ? $timesheetManagerId : null,
            timesheetManagerName: \is_string($timesheetManagerName) ? $timesheetManagerName : null,
            lastValidatedTimesheetDate: \is_string($v = data_get($data, 'last_validated_timesheet_date')) ? $v : null,
            active: \is_bool($active) ? $active : null,
        );
    }
}
