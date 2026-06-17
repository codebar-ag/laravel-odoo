<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Requests\Api\Employees;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class GetEmployeeByUserIdRequest extends Request implements HasBody
{
    use HasJsonBody;

    private const DEFAULT_FIELDS = [
        'id', 'name', 'work_email', 'work_phone', 'mobile_phone', 'job_title',
        'department_id', 'job_id', 'parent_id', 'coach_id', 'user_id', 'company_id',
        'timesheet_manager_id', 'last_validated_timesheet_date', 'active',
    ];

    protected Method $method = Method::POST;

    /** @param array<string> $fields */
    public function __construct(
        private readonly int $userId,
        private readonly array $fields = [],
        private readonly int $limit = 1,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/json/2/hr.employee/search_read';
    }

    /** @return array<string, mixed> */
    protected function defaultBody(): array
    {
        return [
            'domain' => [['user_id', '=', $this->userId]],
            'fields' => $this->fields ?: self::DEFAULT_FIELDS,
            'limit' => $this->limit,
        ];
    }
}
