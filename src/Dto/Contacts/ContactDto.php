<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Dto\Contacts;

use CodebarAg\Odoo\Data\Casts\IntCast;
use CodebarAg\Odoo\Data\Casts\OdooRelationCast;
use CodebarAg\Odoo\Data\Enums\RelationPart;
use CodebarAg\Odoo\Data\OdooData;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;

class ContactDto extends OdooData
{
    public function __construct(
        #[WithCast(IntCast::class)]
        public int $id = 0,
        public string $name = '',
        public ?string $email = null,
        public ?string $phone = null,
        public ?string $street = null,
        public ?string $city = null,
        public ?string $zip = null,
        #[WithCast(OdooRelationCast::class, RelationPart::Id)]
        public ?int $countryId = null,
        #[MapInputName('country_id'), WithCast(OdooRelationCast::class, RelationPart::Name)]
        public ?string $countryName = null,
        public bool $isCompany = false,
        #[WithCast(OdooRelationCast::class, RelationPart::Id)]
        public ?int $parentId = null,
        #[MapInputName('parent_id'), WithCast(OdooRelationCast::class, RelationPart::Name)]
        public ?string $parentName = null,
    ) {}

    /** @param array<array-key, mixed> $data */
    public static function fromArray(array $data): self
    {
        return self::hydrate($data);
    }
}
