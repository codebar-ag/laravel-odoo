<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Requests\CallKw\Fields;

use CodebarAg\Odoo\Requests\CallKw\CallKwRequest;

class GetFieldsRequest extends CallKwRequest
{
    /** @param array<string> $attributes */
    public function __construct(
        private readonly string $model,
        private readonly array $attributes = ['string', 'type', 'required'],
    ) {}

    protected function getModel(): string
    {
        return $this->model;
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
        return ['attributes' => $this->attributes];
    }
}
