<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Requests\CallKw\Employees;

use CodebarAg\Odoo\Requests\CallKw\CallKwRequest;

class GetEmployeeByUserIdRequest extends CallKwRequest
{
    public function __construct(private readonly int $userId) {}

    protected function getModel(): string
    {
        return 'hr.employee';
    }

    protected function getOdooMethod(): string
    {
        return 'search_read';
    }

    protected function getArgs(): array
    {
        return [[['user_id', '=', $this->userId]]];
    }

    protected function getKwargs(): array
    {
        return [
            'fields' => ['id', 'name', 'job_title', 'work_email', 'user_id', 'department_id'],
            'limit' => 1,
        ];
    }
}
