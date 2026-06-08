<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Requests\CallKw\Fields;

use CodebarAg\Odoo\Requests\CallKw\CallKwRequest;

class GetAllFieldsRequest extends CallKwRequest
{
    protected function getModel(): string
    {
        return 'account.analytic.line';
    }

    protected function getOdooMethod(): string
    {
        return 'fields_get';
    }

    protected function getArgs(): array
    {
        return [];
    }

    protected function getKwargs(): array
    {
        return ['attributes' => ['string', 'type', 'required']];
    }
}
