<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Requests\Api\Models;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Generic `write` on any Odoo model: applies the same values to every given record.
 */
class WriteRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  array<int>  $ids
     * @param  array<string, mixed>  $values
     */
    public function __construct(
        private readonly string $model,
        private readonly array $ids,
        private readonly array $values,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/json/2/{$this->model}/write";
    }

    /** @return array<string, mixed> */
    protected function defaultBody(): array
    {
        return [
            'ids' => $this->ids,
            'vals' => $this->values,
        ];
    }
}
