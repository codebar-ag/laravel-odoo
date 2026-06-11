<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Responses\Api\Timesheets;

use CodebarAg\Odoo\Responses\OdooResponse;

class MutateTimesheetResponse extends OdooResponse
{
    public function ok(): bool
    {
        return $this->response->successful();
    }
}
