<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Requests\CallKw\Timesheets;

use CodebarAg\Odoo\Requests\CallKw\CallKwRequest;

class GetTimesheetEntriesRequest extends CallKwRequest
{
    private const DEFAULT_FIELDS = ['id', 'name', 'project_id', 'task_id', 'unit_amount', 'date', 'employee_id'];

    /** @param array<string> $fields @param array<mixed> $domain */
    public function __construct(
        private readonly array $fields = [],
        private readonly array $domain = [],
        private readonly int $limit = 100,
    ) {}

    protected function getModel(): string
    {
        return 'account.analytic.line';
    }

    protected function getOdooMethod(): string
    {
        return 'search_read';
    }

    protected function getArgs(): array
    {
        return [];
    }

    protected function getKwargs(): array
    {
        return [
            'fields' => $this->fields ?: self::DEFAULT_FIELDS,
            'domain' => $this->domain,
            'limit' => $this->limit,
        ];
    }
}
