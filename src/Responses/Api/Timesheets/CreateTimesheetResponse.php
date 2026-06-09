<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Responses\Api\Timesheets;

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

        $body = $this->response->json();

        if (is_int($body)) {
            return $body;
        }

        $id = $this->response->json('id');

        return is_int($id) ? $id : null;
    }
}
