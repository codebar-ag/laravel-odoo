<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Requests\Api\Tasks;

use CodebarAg\Odoo\Requests\Api\Tasks\Concerns\HasTaskFields;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class GetTasksByProjectRequest extends Request implements HasBody
{
    use HasJsonBody;
    use HasTaskFields;

    protected Method $method = Method::POST;

    /** @param array<string> $fields */
    public function __construct(
        private readonly int $projectId,
        private readonly array $fields = [],
        private readonly int $limit = 100,
        private readonly string $operator = '=',
    ) {}

    public function resolveEndpoint(): string
    {
        return '/json/2/project.task/search_read';
    }

    /** @return array<string, mixed> */
    protected function defaultBody(): array
    {
        return [
            'domain' => [['project_id', $this->operator, $this->projectId]],
            'fields' => $this->fields ?: self::DEFAULT_FIELDS,
            'limit' => $this->limit,
        ];
    }
}
