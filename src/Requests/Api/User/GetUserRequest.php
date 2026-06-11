<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Requests\Api\User;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class GetUserRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return '/json/2/res.users/search_read';
    }

    /** @return array<string, mixed> */
    protected function defaultBody(): array
    {
        return [
            'domain' => [],
            'fields' => ['id', 'name', 'lang'],
            'limit' => 1,
        ];
    }
}
