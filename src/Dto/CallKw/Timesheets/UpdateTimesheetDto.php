<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Dto\CallKw\Timesheets;

readonly class UpdateTimesheetDto
{
    /** @param array<string, mixed> $values */
    public function __construct(
        public int $id,
        public array $values,
    ) {}
}
