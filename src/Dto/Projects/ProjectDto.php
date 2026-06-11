<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Dto\Projects;

use CodebarAg\Odoo\Data\Casts\IntCast;
use CodebarAg\Odoo\Data\Casts\IntListCast;
use CodebarAg\Odoo\Data\Casts\OdooRelationCast;
use CodebarAg\Odoo\Data\Enums\RelationPart;
use CodebarAg\Odoo\Data\OdooData;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;

class ProjectDto extends OdooData
{
    /**
     * @param  array<int>  $tagIds
     */
    public function __construct(
        #[WithCast(IntCast::class)]
        public int $id = 0,
        public string $name = '',
        public ?string $description = null,
        #[WithCast(OdooRelationCast::class, RelationPart::Id)]
        public ?int $partnerId = null,
        #[MapInputName('partner_id'), WithCast(OdooRelationCast::class, RelationPart::Name)]
        public ?string $partnerName = null,
        #[WithCast(OdooRelationCast::class, RelationPart::Id)]
        public ?int $userId = null,
        #[MapInputName('user_id'), WithCast(OdooRelationCast::class, RelationPart::Name)]
        public ?string $userName = null,
        public ?string $dateStart = null,
        public ?string $date = null,
        public ?bool $active = null,
        #[WithCast(OdooRelationCast::class, RelationPart::Id)]
        public ?int $companyId = null,
        #[MapInputName('company_id'), WithCast(OdooRelationCast::class, RelationPart::Name)]
        public ?string $companyName = null,
        public ?float $allocatedHours = null,
        public ?float $effectiveHours = null,
        public ?float $remainingHours = null,
        public ?int $taskCount = null,
        public ?string $lastUpdateStatus = null,
        public ?string $privacyVisibility = null,
        #[WithCast(IntListCast::class)]
        public array $tagIds = [],
        public ?int $color = null,
        public ?string $createDate = null,
        public ?string $writeDate = null,
    ) {}

    /** @param array<array-key, mixed> $data */
    public static function fromArray(array $data): self
    {
        return self::hydrate($data);
    }
}
