<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Requests\Api\Tasks;

use CodebarAg\Odoo\Requests\Api\Tasks\Concerns\HasTaskFields;
use CodebarAg\Odoo\Requests\Concerns\HasOdooCaching;
use Saloon\CachePlugin\Contracts\Cacheable;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class GetAllTasksRequest extends Request implements Cacheable, HasBody
{
    use HasJsonBody;
    use HasOdooCaching;
    use HasTaskFields;

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
        return '/json/2/project.task/search_read';
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
