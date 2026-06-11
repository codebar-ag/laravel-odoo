<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Responses\Api\Timesheets;

use CodebarAg\Odoo\Dto\Timesheets\TimesheetEntryDto;
use CodebarAg\Odoo\Responses\OdooResponse;

class TimesheetEntriesResponse extends OdooResponse
{
    /** @return array<TimesheetEntryDto> */
    public function entries(): array
    {
        if ($this->failed()) {
            return [];
        }

        $result = $this->response->json();

        $entries = [];
        foreach ($result as $item) {
            if (\is_array($item)) {
                $entries[] = TimesheetEntryDto::fromArray($item);
            }
        }

        return $entries;
    }
}
