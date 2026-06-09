<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Requests\Api\Projects;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class GetProjectsRequest extends Request implements HasBody
{
    use HasJsonBody;

    private const DEFAULT_FIELDS = ['id', 'name', 'description', 'partner_id', 'user_id', 'date_start', 'date'];

    protected Method $method = Method::POST;

    /** @param array<string> $fields @param array<mixed> $domain */
    public function __construct(
        private readonly array $fields = [],
        private readonly array $domain = [],
        private readonly int $limit = 100,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/json/2/project.project/search_read';
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
