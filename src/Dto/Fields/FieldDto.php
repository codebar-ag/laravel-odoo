<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Dto\Fields;

use CodebarAg\Odoo\Data\OdooData;
use Spatie\LaravelData\Attributes\MapInputName;

class FieldDto extends OdooData
{
    public function __construct(
        public string $name = '',
        public string $type = 'char',
        #[MapInputName('string')]
        public string $label = '',
        public bool $required = false,
        public bool $readonly = false,
        public ?string $relation = null,
    ) {}

    /** @param array<array-key, mixed> $data */
    public static function fromArray(string $name, array $data): self
    {
        $label = data_get($data, 'string');

        return self::hydrate([
            ...$data,
            'name' => $name,
            'string' => \is_string($label) ? $label : $name,
        ]);
    }
}
