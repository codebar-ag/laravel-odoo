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

    private const DEFAULT_FIELDS = ['id', 'name', 'job_title', 'work_email', 'user_id', 'department_id'];

    protected Method $method = Method::POST;

    /** @param array<string> $fields */
    public function __construct(
        private readonly int $userId,
        private readonly array $fields = [],
    ) {
    }

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
            'limit' => 1,
        ];
    }
}
