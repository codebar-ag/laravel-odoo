<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Requests\CallKw\Tasks;

use CodebarAg\Odoo\Requests\CallKw\CallKwRequest;

class GetAllTasksRequest extends CallKwRequest
{
    private const DEFAULT_FIELDS = ['id', 'name', 'description', 'project_id', 'user_ids', 'stage_id', 'date_deadline', 'priority'];

    /** @param array<string> $fields @param array<mixed> $domain */
    public function __construct(
        private readonly array $fields = [],
        private readonly array $domain = [],
        private readonly int $limit = 100,
    ) {
    }

    protected function getModel(): string
    {
        return 'project.task';
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
