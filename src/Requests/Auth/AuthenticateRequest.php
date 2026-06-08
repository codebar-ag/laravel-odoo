<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Requests\Auth;

use CodebarAg\Odoo\Dto\Auth\AuthenticateDto;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class AuthenticateRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(private readonly AuthenticateDto $dto) {}

    public function resolveEndpoint(): string
    {
        return '/web/session/authenticate';
    }

    /** @return array<string, mixed> */
    protected function defaultBody(): array
    {
        return [
            'jsonrpc' => '2.0',
            'method' => 'call',
            'params' => $this->dto->toArray(),
        ];
    }
}
