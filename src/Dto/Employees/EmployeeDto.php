<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Dto\Employees;

readonly class EmployeeDto
{
    public function __construct(
        public int $id,
        public string $name,
    ) {}

    /** @param array<array-key, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            id: \is_int($v = data_get($data, 'id', 0)) ? $v : 0,
            name: \is_string($v = data_get($data, 'name', '')) ? $v : '',
        );
    }
}
