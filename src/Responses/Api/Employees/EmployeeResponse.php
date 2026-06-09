<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Responses\Api\Employees;

use CodebarAg\Odoo\Dto\Employees\EmployeeDto;
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

        if (empty($result) || ! \is_array($result[0])) {
            return null;
        }

        /** @var array{id: int, name: string} $employee */
        $employee = $result[0];

        return EmployeeDto::fromArray($employee);
    }
}
