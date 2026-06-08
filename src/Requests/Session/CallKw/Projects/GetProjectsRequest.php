<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Requests\CallKw\Projects;

use CodebarAg\Odoo\Requests\CallKw\CallKwRequest;

class GetProjectsRequest extends CallKwRequest
{
    private const DEFAULT_FIELDS = ['id', 'name', 'description', 'partner_id', 'user_id', 'date_start', 'date'];

    /** @param array<string> $fields @param array<mixed> $domain */
    public function __construct(
        private readonly array $fields = [],
        private readonly array $domain = [],
        private readonly int $limit = 100,
    ) {
    }

    protected function getModel(): string
    {
        return 'project.project';
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
