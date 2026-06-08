<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Requests\Auth\Passkey;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetPasskeyOptionsRequest extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/api/auth/passkey/login_options';
    }
}
