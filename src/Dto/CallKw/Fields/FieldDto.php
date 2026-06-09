<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Dto\CallKw\Fields;

use Illuminate\Support\Arr;

readonly class FieldDto
{
    public function __construct(
        public string $name,
        public string $type,
        public string $label,
        public bool $required,
    ) {}

    /** @param array<string, mixed> $data */
    public static function fromArray(string $name, array $data): self
    {
        return new self(
            name: $name,
            type: \strval(Arr::get($data, 'type') ?? 'char'),
            label: \strval(Arr::get($data, 'string') ?? $name),
            required: (bool) Arr::get($data, 'required', false),
        );
    }
}
