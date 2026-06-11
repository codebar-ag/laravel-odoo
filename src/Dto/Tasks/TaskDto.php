<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Dto\Tasks;

use CodebarAg\Odoo\Data\Casts\IntCast;
use CodebarAg\Odoo\Data\Casts\IntListCast;
use CodebarAg\Odoo\Data\Casts\OdooRelationCast;
use CodebarAg\Odoo\Data\Enums\RelationPart;
use CodebarAg\Odoo\Data\OdooData;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;

class TaskDto extends OdooData
{
    /**
     * @param  array<int>  $userIds
     * @param  array<int>  $childIds
     * @param  array<int>  $tagIds
     */
    public function __construct(
        #[WithCast(IntCast::class)]
        public int $id = 0,
        public string $name = '',
        public ?string $description = null,
        #[WithCast(OdooRelationCast::class, RelationPart::Id)]
        public ?int $projectId = null,
        #[MapInputName('project_id'), WithCast(OdooRelationCast::class, RelationPart::Name)]
        public ?string $projectName = null,
        #[WithCast(IntListCast::class)]
        public array $userIds = [],
        #[WithCast(OdooRelationCast::class, RelationPart::Id)]
        public ?int $stageId = null,
        #[MapInputName('stage_id'), WithCast(OdooRelationCast::class, RelationPart::Name)]
        public ?string $stageName = null,
        public ?string $dateDeadline = null,
        public string $priority = '0',
        public ?bool $active = null,
        public ?string $state = null,
        #[WithCast(OdooRelationCast::class, RelationPart::Id)]
        public ?int $partnerId = null,
        #[MapInputName('partner_id'), WithCast(OdooRelationCast::class, RelationPart::Name)]
        public ?string $partnerName = null,
        #[WithCast(OdooRelationCast::class, RelationPart::Id)]
        public ?int $companyId = null,
        #[MapInputName('company_id'), WithCast(OdooRelationCast::class, RelationPart::Name)]
        public ?string $companyName = null,
        #[WithCast(OdooRelationCast::class, RelationPart::Id)]
        public ?int $parentId = null,
        #[MapInputName('parent_id'), WithCast(OdooRelationCast::class, RelationPart::Name)]
        public ?string $parentName = null,
        #[WithCast(OdooRelationCast::class, RelationPart::Id)]
        public ?int $milestoneId = null,
        #[MapInputName('milestone_id'), WithCast(OdooRelationCast::class, RelationPart::Name)]
        public ?string $milestoneName = null,
        public ?float $allocatedHours = null,
        public ?float $effectiveHours = null,
        public ?float $remainingHours = null,
        public ?float $totalHoursSpent = null,
        public ?float $progress = null,
        public ?int $subtaskCount = null,
        #[WithCast(IntListCast::class)]
        public array $childIds = [],
        #[WithCast(IntListCast::class)]
        public array $tagIds = [],
        public ?string $dateAssign = null,
        public ?string $dateLastStageUpdate = null,
        public ?string $createDate = null,
        public ?string $writeDate = null,
    ) {}

    /** @param array<array-key, mixed> $data */
    public static function fromArray(array $data): self
    {
        return self::hydrate($data);
    }
}
