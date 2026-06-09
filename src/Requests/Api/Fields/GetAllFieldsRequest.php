<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Requests\Api\Fields;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class GetAllFieldsRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return '/json/2/account.analytic.line/fields_get';
    }

    /** @return array<string, mixed> */
    protected function defaultBody(): array
    {
        return [
            'attributes' => ['string', 'type', 'required'],
        ];
    }
}
