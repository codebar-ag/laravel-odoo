<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Requests\Api\Fields;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class GetFieldsRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /** @param array<string> $attributes */
    public function __construct(
        private readonly string $model,
        private readonly array $attributes = ['string', 'type', 'required', 'readonly', 'relation'],
    ) {}

    public function resolveEndpoint(): string
    {
        return "/json/2/{$this->model}/fields_get";
    }

    /** @return array<string, mixed> */
    protected function defaultBody(): array
    {
        return [
            'attributes' => $this->attributes,
        ];
    }
}
