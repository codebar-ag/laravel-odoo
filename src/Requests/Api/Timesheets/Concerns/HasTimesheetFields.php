<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Requests\Api\Timesheets\Concerns;

trait HasTimesheetFields
{
    /** @var array<string> */
    private const DEFAULT_FIELDS = [
        'id', 'name', 'project_id', 'task_id', 'unit_amount', 'date', 'employee_id',
        'user_id', 'validated', 'validated_status', 'user_can_validate', 'readonly_timesheet',
        'amount', 'company_id', 'partner_id', 'create_date', 'write_date',
    ];
}
