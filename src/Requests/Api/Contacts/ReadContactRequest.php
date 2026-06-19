<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Requests\Api\Contacts;

use CodebarAg\Odoo\Requests\Api\Contacts\Concerns\HasContactFields;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class ReadContactRequest extends Request implements HasBody
{
    use HasJsonBody;
    use HasContactFields;

    protected Method $method = Method::POST;

    /** @param array<string> $fields */
    public function __construct(
        private readonly int $id,
        private readonly array $fields = [],
    ) {}

    public function resolveEndpoint(): string
    {
        return '/json/2/res.partner/read';
    }

    /** @return array<string, mixed> */
    protected function defaultBody(): array
    {
        return [
            'ids' => [$this->id],
            'fields' => $this->fields ?: self::DEFAULT_FIELDS,
        ];
    }
}
