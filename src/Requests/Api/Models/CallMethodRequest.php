<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Requests\Api\Models;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Calls any public model method, e.g. `sale.order` / `action_confirm`.
 * The params are sent as the JSON body unchanged (e.g. `ids`, `context`, keyword arguments).
 */
class CallMethodRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /** @param array<string, mixed> $params */
    public function __construct(
        private readonly string $model,
        private readonly string $modelMethod,
        private readonly array $params = [],
    ) {}

    public function resolveEndpoint(): string
    {
        return "/json/2/{$this->model}/{$this->modelMethod}";
    }

    /** @return array<string, mixed> */
    protected function defaultBody(): array
    {
        return $this->params;
    }
}
