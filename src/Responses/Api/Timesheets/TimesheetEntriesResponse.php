<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Responses\Api\Timesheets;

use CodebarAg\Odoo\Dto\Timesheets\TimesheetEntryDto;
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
