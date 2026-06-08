<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Requests\Session\Version;

use Saloon\Enums\Method;
use Saloon\Http\Request;
class GetOdooVersionRequest extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/web/version';
    }
}
