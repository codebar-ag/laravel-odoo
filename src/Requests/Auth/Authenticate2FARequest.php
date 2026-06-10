<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Requests\Auth;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasFormBody;

class Authenticate2FARequest extends Request implements HasBody
{
    use HasFormBody;

    protected Method $method = Method::POST;

    /** @param array<string, mixed> $params */
    public function __construct(protected array $params) {}

    public function resolveEndpoint(): string
    {
        return '/web/login/totp';
    }

    /** @return array<string, mixed> */
    protected function defaultBody(): array
    {
        return $this->params;
    }
}
