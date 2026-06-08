<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Requests\Session\Database;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetDatabasesRequest extends Request
{
    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return '/web/database/list';
    }
}
