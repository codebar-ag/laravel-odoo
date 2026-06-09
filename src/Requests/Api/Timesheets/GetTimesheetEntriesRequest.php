<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Requests\Api\Timesheets;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class GetTimesheetEntriesRequest extends Request implements HasBody
{
    use HasJsonBody;

    private const DEFAULT_FIELDS = ['id', 'name', 'project_id', 'task_id', 'unit_amount', 'date', 'employee_id'];

    protected Method $method = Method::POST;

    /**
     * @param  array<string>  $fields
     * @param  array<mixed>  $domain
     */
    public function __construct(
        private readonly array $fields = [],
        private readonly array $domain = [],
        private readonly int $limit = 100,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/json/2/account.analytic.line/search_read';
    }

    /** @return array<string, mixed> */
    protected function defaultBody(): array
    {
        return [
            'domain' => $this->domain,
            'fields' => $this->fields ?: self::DEFAULT_FIELDS,
            'limit' => $this->limit,
        ];
    }
}
