<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Dto\CallKw\Permissions;

readonly class PermissionDto
{
    /** @param array<mixed> $args @param array<string, mixed> $kwargs */
    public function __construct(
        public string $model,
        public string $method,
        public array $args = [],
        public array $kwargs = [],
    ) {}
}
