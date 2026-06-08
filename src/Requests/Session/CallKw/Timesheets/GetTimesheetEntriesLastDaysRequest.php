<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Requests\CallKw\Timesheets;

use CodebarAg\Odoo\Requests\CallKw\CallKwRequest;

class GetTimesheetEntriesLastDaysRequest extends CallKwRequest
{
    private const DEFAULT_FIELDS = ['id', 'name', 'project_id', 'task_id', 'unit_amount', 'date', 'employee_id'];

    /** @param array<string> $fields */
    public function __construct(
        private readonly int $days,
        private readonly array $fields = [],
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
        $since = date('Y-m-d', strtotime("-{$this->days} days"));

        return [
            'fields' => $this->fields ?: self::DEFAULT_FIELDS,
            'domain' => [['date', '>=', $since]],
        ];
    }
}
