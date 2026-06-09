<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Requests\Api\Permissions;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class GetPermissionsRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        private readonly string $model,
        private readonly string $operation,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/json/2/{$this->model}/has_access";
    }

    /** @return array<string, mixed> */
    protected function defaultBody(): array
    {
        return [
            'operation' => $this->operation,
        ];
    }
}
