<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Responses\CallKw\Timesheets;

use CodebarAg\Odoo\Dto\CallKw\Timesheets\TimesheetEntryDto;
use CodebarAg\Odoo\Responses\OdooResponse;
use Saloon\Http\Response;

class TimesheetEntriesResponse extends OdooResponse
{
    private function __construct(Response $response)
    {
        parent::__construct($response);
    }

    public static function fromResponse(Response $response): self
    {
        return new self($response);
    }

    /** @return array<TimesheetEntryDto> */
    public function entries(): array
    {
        if ($this->failed()) {
            return [];
        }

        $result = $this->response->json('result');

        if (! \is_array($result)) {
            return [];
        }

        return array_map(TimesheetEntryDto::fromArray(...), $result);
    }
}
