<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Requests\Api\User;

use CodebarAg\Odoo\Requests\Concerns\HasOdooCaching;
use Saloon\CachePlugin\Contracts\Cacheable;
use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetUserContextRequest extends Request implements Cacheable
{
    use HasOdooCaching;    
    
    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return '/json/2/res.users/context_get';
    }
}
