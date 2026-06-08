<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Requests\Auth\BasicAuth;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class AuthenticateRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /** @param array<string, mixed> $params */
    public function __construct(protected array $params) {}

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
            'id' => 1,
            'params' => $this->params,
        ];
    }
}
