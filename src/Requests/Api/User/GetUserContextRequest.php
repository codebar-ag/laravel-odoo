<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Requests\Api\User;

use CodebarAg\Odoo\Requests\Concerns\HasOdooCaching;
use Saloon\CachePlugin\Contracts\Cacheable;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class GetUserContextRequest extends Request implements Cacheable, HasBody
{
    use HasJsonBody;
    use HasOdooCaching;

    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return '/json/2/res.users/context_get';
    }

    /** @return array<string, mixed> */
    protected function defaultBody(): array
    {
        return [
            'domain' => [],
            'fields' => [
                'id', 'tz', 'lang',
            ],
            'limit' => 1,
        ];
    }
}
