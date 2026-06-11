<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Dto\Employees;

use CodebarAg\Odoo\Data\Casts\IntCast;
use CodebarAg\Odoo\Data\Casts\OdooRelationCast;
use CodebarAg\Odoo\Data\Enums\RelationPart;
use CodebarAg\Odoo\Data\OdooData;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;

class EmployeeDto extends OdooData
{
    public function __construct(
        #[WithCast(IntCast::class)]
        public int $id = 0,
        public string $name = '',
        public ?string $workEmail = null,
        public ?string $workPhone = null,
        public ?string $mobilePhone = null,
        public ?string $jobTitle = null,
        #[WithCast(OdooRelationCast::class, RelationPart::Id)]
        public ?int $departmentId = null,
        #[MapInputName('department_id'), WithCast(OdooRelationCast::class, RelationPart::Name)]
        public ?string $departmentName = null,
        #[WithCast(OdooRelationCast::class, RelationPart::Id)]
        public ?int $jobId = null,
        #[MapInputName('job_id'), WithCast(OdooRelationCast::class, RelationPart::Name)]
        public ?string $jobName = null,
        #[WithCast(OdooRelationCast::class, RelationPart::Id)]
        public ?int $parentId = null,
        #[MapInputName('parent_id'), WithCast(OdooRelationCast::class, RelationPart::Name)]
        public ?string $parentName = null,
        #[WithCast(OdooRelationCast::class, RelationPart::Id)]
        public ?int $coachId = null,
        #[MapInputName('coach_id'), WithCast(OdooRelationCast::class, RelationPart::Name)]
        public ?string $coachName = null,
        #[WithCast(OdooRelationCast::class, RelationPart::Id)]
        public ?int $userId = null,
        #[MapInputName('user_id'), WithCast(OdooRelationCast::class, RelationPart::Name)]
        public ?string $userName = null,
        #[WithCast(OdooRelationCast::class, RelationPart::Id)]
        public ?int $companyId = null,
        #[MapInputName('company_id'), WithCast(OdooRelationCast::class, RelationPart::Name)]
        public ?string $companyName = null,
        #[WithCast(OdooRelationCast::class, RelationPart::Id)]
        public ?int $timesheetManagerId = null,
        #[MapInputName('timesheet_manager_id'), WithCast(OdooRelationCast::class, RelationPart::Name)]
        public ?string $timesheetManagerName = null,
        public ?string $lastValidatedTimesheetDate = null,
        public ?bool $active = null,
    ) {}

    /** @param array<array-key, mixed> $data */
    public static function fromArray(array $data): self
    {
        return self::hydrate($data);
    }
}
