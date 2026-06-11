<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Dto\Fields;

readonly class FieldDto
{
    public function __construct(
        public string $name,
        public string $type,
        public string $label,
        public bool $required,
    ) {
    }

    /** @param array<array-key, mixed> $data */
    public static function fromArray(string $name, array $data): self
    {
        $type = $data['type'] ?? 'char';
        $label = $data['string'] ?? $name;
        $required = $data['required'] ?? false;

        return new self(
            name: $name,
            type: \is_string($type) ? $type : 'char',
            label: \is_string($label) ? $label : $name,
            required: (bool) $required,
        );
    }
}
