<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Requests\Session\Auth;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class LogoutRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return '/web/session/destroy';
    }

    /** @return array<string, mixed> */
    protected function defaultBody(): array
    {
        return [
            'jsonrpc' => '2.0',
            'method' => 'call',
            'params' => [],
        ];
    }
}
