<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Responses\Api\Timesheets;

use CodebarAg\Odoo\Dto\Timesheets\TimesheetEntryDto;
use CodebarAg\Odoo\Responses\OdooResponse;
use Saloon\Http\Response;

class TimesheetResponse extends OdooResponse
{
    private function __construct(Response $response)
    {
        parent::__construct($response);
    }

    public static function fromResponse(Response $response): self
    {
        return new self($response);
    }

    public function dto(): ?TimesheetEntryDto
    {
        if ($this->failed()) {
            return null;
        }

        $result = $this->response->json();

        if (empty($result)) {
            return null;
        }

        $first = $result[0] ?? null;
        $record = \is_array($first) ? $first : $result;

        return TimesheetEntryDto::fromArray($record);
    }
}
