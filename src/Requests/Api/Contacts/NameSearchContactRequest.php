<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Requests\Api\Contacts;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class NameSearchContactRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /** @param array<mixed> $domain */
    public function __construct(
        private readonly string $name,
        private readonly array $domain = [],
        private readonly int $limit = 100,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/json/2/res.partner/name_search';
    }

    /** @return array<string, mixed> */
    protected function defaultBody(): array
    {
        return [
            'name' => $this->name,
            'domain' => $this->domain,
            'limit' => $this->limit,
        ];
    }
}
