<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Requests\Api\Contacts;

use CodebarAg\Odoo\Requests\Api\Contacts\Concerns\HasContactFields;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class ReadAllContactRequest extends Request implements HasBody
{
    use HasContactFields;
    use HasJsonBody;

    protected Method $method = Method::POST;

    /** @param array<string> $fields */
    public function __construct(
        private readonly array $fields = [],
    ) {}

    public function resolveEndpoint(): string
    {
        return '/json/2/res.partner/search_read';
    }

    /** @return array<string, mixed> */
    protected function defaultBody(): array
    {
        return [
            'domain' => [],
            'fields' => $this->fields ?: self::DEFAULT_FIELDS,
        ];
    }
}
