<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Requests\Api\Models;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Generic `search_read` on any Odoo model, with paging and ordering.
 */
class SearchReadRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  array<mixed>  $domain
     * @param  array<string>  $fields
     */
    public function __construct(
        private readonly string $model,
        private readonly array $domain = [],
        private readonly array $fields = [],
        private readonly int $limit = 80,
        private readonly int $offset = 0,
        private readonly ?string $order = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/json/2/{$this->model}/search_read";
    }

    /** @return array<string, mixed> */
    protected function defaultBody(): array
    {
        return array_filter([
            'domain' => $this->domain,
            'fields' => $this->fields,
            'limit' => $this->limit,
            'offset' => $this->offset,
            'order' => $this->order,
        ], fn (mixed $value): bool => $value !== null);
    }
}
