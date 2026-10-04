<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Requests\Api\Models;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Generic `create` on any Odoo model for a single record.
 */
class CreateRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /** @param array<string, mixed> $values */
    public function __construct(
        private readonly string $model,
        private readonly array $values,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/json/2/{$this->model}/create";
    }

    /** @return array<string, mixed> */
    protected function defaultBody(): array
    {
        return [
            'vals_list' => [$this->values],
        ];
    }
}
