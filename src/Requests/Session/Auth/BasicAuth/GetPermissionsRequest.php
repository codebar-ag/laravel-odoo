<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Requests\Session\Auth\BasicAuth;

use CodebarAg\Odoo\Dto\Session\Permissions\PermissionDto;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class GetPermissionsRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(private readonly PermissionDto $dto)
    {
    }

    public function resolveEndpoint(): string
    {
        return '/web/dataset/call_kw';
    }

    /** @return array<string, mixed> */
    protected function defaultBody(): array
    {
        return [
            'jsonrpc' => '2.0',
            'method' => 'call',
            'params' => [
                'model' => $this->dto->model,
                'method' => 'check_access_rights',
                'args' => [$this->dto->method],
                'kwargs' => ['raise_exception' => false],
            ],
        ];
    }
}
