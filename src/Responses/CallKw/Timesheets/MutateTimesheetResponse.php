<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Responses\CallKw\Timesheets;

use CodebarAg\Odoo\Responses\OdooResponse;
use Saloon\Http\Response;

class MutateTimesheetResponse extends OdooResponse
{
    private function __construct(Response $response)
    {
        parent::__construct($response);
    }

    public static function fromResponse(Response $response): self
    {
        return new self($response);
    }

    public function ok(): bool
    {
        if ($this->failed()) {
            return false;
        }

        return $this->response->json('result') === true;
    }
}
