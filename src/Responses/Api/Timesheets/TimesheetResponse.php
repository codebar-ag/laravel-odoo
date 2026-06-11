<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Responses\Api\Timesheets;

use CodebarAg\Odoo\Dto\Timesheets\TimesheetEntryDto;
use CodebarAg\Odoo\Responses\OdooResponse;

class TimesheetResponse extends OdooResponse
{
    public function dto(): ?TimesheetEntryDto
    {
        if ($this->failed()) {
            return null;
        }

        $result = $this->response->json();

        if (blank($result)) {
            return null;
        }

        $first = data_get($result, 0);
        $record = \is_array($first) ? $first : $result;

        return TimesheetEntryDto::fromArray($record);
    }
}
