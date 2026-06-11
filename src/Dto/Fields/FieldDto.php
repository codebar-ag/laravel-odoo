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
        public bool $readonly,
        public ?string $relation,
    ) {}

    /** @param array<array-key, mixed> $data */
    public static function fromArray(string $name, array $data): self
    {
        $type = data_get($data, 'type', 'char');
        $label = data_get($data, 'string', $name);
        $required = data_get($data, 'required', false);
        $readonly = data_get($data, 'readonly', false);
        $relation = data_get($data, 'relation');

        return new self(
            name: $name,
            type: \is_string($type) ? $type : 'char',
            label: \is_string($label) ? $label : $name,
            required: (bool) $required,
            readonly: (bool) $readonly,
            relation: \is_string($relation) ? $relation : null,
        );
    }
}
