<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Requests\Api\Contacts\Concerns;

trait HasContactFields
{
    /** @var array<string> */
    private const DEFAULT_FIELDS = [
        'id', 'name', 'email', 'phone', 'street', 'city', 'zip', 'country_id', 'is_company', 'parent_id',
    ];
}
