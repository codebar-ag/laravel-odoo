<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Requests\Api\Timesheets\Concerns;

trait HasTimesheetFields
{
    /** @var array<string> */
    private const DEFAULT_FIELDS = ['id', 'name', 'project_id', 'task_id', 'unit_amount', 'date', 'employee_id'];
}
