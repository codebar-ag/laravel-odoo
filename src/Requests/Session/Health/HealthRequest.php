<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Requests\Session\Health;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class HealthRequest extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/web/health';
    }
}
