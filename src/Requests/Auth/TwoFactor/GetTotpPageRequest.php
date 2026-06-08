<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Requests\Auth\TwoFactor;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetTotpPageRequest extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/web/login/totp';
    }
}
