<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Responses\Api\Employees;

use CodebarAg\Odoo\Dto\Employees\EmployeeDto;
use CodebarAg\Odoo\Responses\OdooResponse;

class EmployeeResponse extends OdooResponse
{
    public function dto(): ?EmployeeDto
    {
        if ($this->failed()) {
            return null;
        }

        $employee = data_get($this->response->json(), 0);

        if (! \is_array($employee)) {
            return null;
        }

        /** @var array{id: int, name: string} $employee */
        return EmployeeDto::fromArray($employee);
    }
}
