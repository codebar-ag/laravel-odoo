<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Requests\Api\Contacts;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class SearchCountContactRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /** @param array<mixed> $domain */
    public function __construct(private readonly array $domain = []) {}

    public function resolveEndpoint(): string
    {
        return '/json/2/res.partner/search_count';
    }

    /** @return array<string, mixed> */
    protected function defaultBody(): array
    {
        return ['domain' => $this->domain];
    }
}
