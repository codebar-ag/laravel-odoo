<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Responses\CallKw\Timesheets;

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

        try {
            $result = $this->response->json('result');
        } catch (\JsonException) {
            return null;
        }

        if (! is_array($result) || empty($result)) {
            return null;
        }

        return TimesheetEntryDto::fromArray($result[0]);
    }
}
