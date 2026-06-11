<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Dto\Timesheets;

use CodebarAg\Odoo\Data\Casts\IntCast;
use CodebarAg\Odoo\Data\Casts\OdooRelationCast;
use CodebarAg\Odoo\Data\Enums\RelationPart;
use CodebarAg\Odoo\Data\OdooData;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;

class TimesheetEntryDto extends OdooData
{
    public function __construct(
        #[WithCast(IntCast::class)]
        public int $id = 0,
        public string $name = '',
        #[WithCast(OdooRelationCast::class, RelationPart::Id)]
        public ?int $projectId = null,
        #[MapInputName('project_id'), WithCast(OdooRelationCast::class, RelationPart::Name)]
        public ?string $projectName = null,
        #[WithCast(OdooRelationCast::class, RelationPart::Id)]
        public ?int $taskId = null,
        #[MapInputName('task_id'), WithCast(OdooRelationCast::class, RelationPart::Name)]
        public ?string $taskName = null,
        public float $unitAmount = 0.0,
        public string $date = '',
        #[WithCast(OdooRelationCast::class, RelationPart::Id)]
        public ?int $employeeId = null,
        #[MapInputName('employee_id'), WithCast(OdooRelationCast::class, RelationPart::Name)]
        public ?string $employeeName = null,
        #[WithCast(OdooRelationCast::class, RelationPart::Id)]
        public ?int $userId = null,
        #[MapInputName('user_id'), WithCast(OdooRelationCast::class, RelationPart::Name)]
        public ?string $userName = null,
        public ?bool $validated = null,
        public ?string $validatedStatus = null,
        public ?bool $userCanValidate = null,
        public ?bool $readonlyTimesheet = null,
        public ?float $amount = null,
        #[WithCast(OdooRelationCast::class, RelationPart::Id)]
        public ?int $companyId = null,
        #[MapInputName('company_id'), WithCast(OdooRelationCast::class, RelationPart::Name)]
        public ?string $companyName = null,
        #[WithCast(OdooRelationCast::class, RelationPart::Id)]
        public ?int $partnerId = null,
        #[MapInputName('partner_id'), WithCast(OdooRelationCast::class, RelationPart::Name)]
        public ?string $partnerName = null,
        public ?string $createDate = null,
        public ?string $writeDate = null,
    ) {}

    /** @param array<array-key, mixed> $data */
    public static function fromArray(array $data): self
    {
        return self::hydrate($data);
    }
}
