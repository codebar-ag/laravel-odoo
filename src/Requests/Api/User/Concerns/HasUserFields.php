<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Requests\Api\User\Concerns;

trait HasUserFields
{
    /** @var array<string> */
    private const DEFAULT_FIELDS = [
        'id', 'name', 'lang', 'login', 'email', 'tz', 'job_title',
        'active', 'share', 'partner_id', 'company_id', 'employee_id',
    ];
}
