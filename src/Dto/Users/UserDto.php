<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Dto\Users;

readonly class UserDto
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $lang,
    ) {}

    /** @param array<array-key, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            id: \is_int($v = data_get($data, 'id', 0)) ? $v : 0,
            name: \is_string($v = data_get($data, 'name', '')) ? $v : '',
            lang: \is_string($v = data_get($data, 'lang')) ? $v : null,
        );
    }
}
