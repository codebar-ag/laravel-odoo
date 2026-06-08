<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Responses\CallKw\Timesheets;

use CodebarAg\Odoo\Responses\OdooResponse;
use Saloon\Http\Response;

class CreateTimesheetResponse extends OdooResponse
{
    private function __construct(Response $response)
    {
        parent::__construct($response);
    }

    public static function fromResponse(Response $response): self
    {
        return new self($response);
    }

    public function id(): ?int
    {
        if ($this->failed()) {
            return null;
        }

        try {
            $result = $this->response->json('result');

            return is_int($result) ? $result : null;
        } catch (\JsonException) {
            return null;
        }
    }
}
