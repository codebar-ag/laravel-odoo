<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Responses\Api\Timesheets;

use CodebarAg\Odoo\Dto\CallKw\Timesheets\TimesheetEntryDto;
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

        if (! \is_array($result) || empty($result)) {
            return null;
        }

        $record = isset($result[0]) && \is_array($result[0]) ? $result[0] : $result;

        return TimesheetEntryDto::fromArray($record);
    }
}
