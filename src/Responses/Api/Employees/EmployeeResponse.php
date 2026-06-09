<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Responses\Api\Employees;

use CodebarAg\Odoo\Dto\CallKw\Employees\EmployeeDto;
use CodebarAg\Odoo\Responses\OdooResponse;
use Saloon\Http\Response;

class EmployeeResponse extends OdooResponse
{
    private function __construct(Response $response)
    {
        parent::__construct($response);
    }

    public static function fromResponse(Response $response): self
    {
        return new self($response);
    }

    public function dto(): ?EmployeeDto
    {
        if ($this->failed()) {
            return null;
        }

        $result = $this->response->json();

        if (! \is_array($result) || empty($result)) {
            return null;
        }

        return EmployeeDto::fromArray($result[0]);
    }
}
