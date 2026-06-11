<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Requests\Api\Tasks\Concerns;

trait HasTaskFields
{
    /** @var array<string> */
    private const DEFAULT_FIELDS = [
        'id', 'name', 'description', 'project_id', 'user_ids', 'stage_id', 'date_deadline', 'priority',
        'active', 'state', 'partner_id', 'company_id', 'parent_id', 'milestone_id',
        'allocated_hours', 'effective_hours', 'remaining_hours', 'total_hours_spent', 'progress',
        'subtask_count', 'child_ids', 'tag_ids', 'date_assign', 'date_last_stage_update',
        'create_date', 'write_date',
    ];
}
