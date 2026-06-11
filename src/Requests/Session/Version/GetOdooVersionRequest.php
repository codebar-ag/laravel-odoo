<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Requests\Session\Version;

use CodebarAg\Odoo\Requests\Concerns\HasOdooCaching;
use Saloon\CachePlugin\Contracts\Cacheable;
use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetOdooVersionRequest extends Request implements Cacheable
{
    use HasOdooCaching;

    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/web/version';
    }
}
