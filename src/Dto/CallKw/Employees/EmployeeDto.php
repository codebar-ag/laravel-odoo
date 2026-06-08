<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Dto\CallKw\Employees;

use Illuminate\Support\Arr;

readonly class EmployeeDto
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $jobTitle,
        public ?string $workEmail,
        public ?int $userId,
        public ?string $userLogin,
        public ?int $departmentId,
        public ?string $departmentName,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        $user = Arr::get($data, 'user_id');
        $department = Arr::get($data, 'department_id');

        return new self(
            id: (int) Arr::get($data, 'id'),
            name: (string) Arr::get($data, 'name'),
            jobTitle: ($v = Arr::get($data, 'job_title')) ? (string) $v : null,
            workEmail: ($v = Arr::get($data, 'work_email')) ? (string) $v : null,
            userId: is_array($user) ? (int) $user[0] : null,
            userLogin: is_array($user) ? (string) $user[1] : null,
            departmentId: is_array($department) ? (int) $department[0] : null,
            departmentName: is_array($department) ? (string) $department[1] : null,
        );
    }
}
