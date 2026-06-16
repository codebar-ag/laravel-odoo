<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Requests\Api\User;

use CodebarAg\Odoo\Requests\Concerns\HasOdooCaching;
use Saloon\CachePlugin\Contracts\Cacheable;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class GetUserByIdRequest extends Request implements Cacheable, HasBody
{
    use HasJsonBody;
    use HasOdooCaching;

    protected Method $method = Method::POST;

    public function __construct(
        private readonly int $uid,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/json/2/res.users/search_read';
    }

    /** @return array<string, mixed> */
    protected function defaultBody(): array
    {
        return [
            "domain" => [["id", "=", $this->uid]],
            "fields" => ["id", "name", "lang", "login", "email", "tz", "active", "share", "partner_id", "company_id"],
            "limit" => 1,
        ];
    }
}
