<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Dto\Session\Permissions;

readonly class PermissionDto
{
    public function __construct(
        public string $model,
        public string $method,
        public array $args = [],
        public array $kwargs = [],
    ) {}

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'model' => $this->model,
            'method' => $this->method,
            'args' => $this->args,
            'kwargs' => $this->kwargs,
        ];
    }
}
