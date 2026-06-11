<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Dto\Users;

use CodebarAg\Odoo\Data\Casts\IntCast;
use CodebarAg\Odoo\Data\Casts\OdooRelationCast;
use CodebarAg\Odoo\Data\Enums\RelationPart;
use CodebarAg\Odoo\Data\OdooData;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;

class UserDto extends OdooData
{
    public function __construct(
        #[WithCast(IntCast::class)]
        public int $id = 0,
        public string $name = '',
        public ?string $lang = null,
        public ?string $login = null,
        public ?string $email = null,
        public ?string $tz = null,
        public ?string $jobTitle = null,
        public ?bool $active = null,
        public ?bool $share = null,
        #[WithCast(OdooRelationCast::class, RelationPart::Id)]
        public ?int $partnerId = null,
        #[MapInputName('partner_id'), WithCast(OdooRelationCast::class, RelationPart::Name)]
        public ?string $partnerName = null,
        #[WithCast(OdooRelationCast::class, RelationPart::Id)]
        public ?int $companyId = null,
        #[MapInputName('company_id'), WithCast(OdooRelationCast::class, RelationPart::Name)]
        public ?string $companyName = null,
        #[WithCast(OdooRelationCast::class, RelationPart::Id)]
        public ?int $employeeId = null,
        #[MapInputName('employee_id'), WithCast(OdooRelationCast::class, RelationPart::Name)]
        public ?string $employeeName = null,
    ) {}

    /** @param array<array-key, mixed> $data */
    public static function fromArray(array $data): self
    {
        return self::hydrate($data);
    }
}
