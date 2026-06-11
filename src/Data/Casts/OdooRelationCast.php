<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Data\Casts;

use CodebarAg\Odoo\Data\Enums\RelationPart;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Contracts\BaseData;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

/**
 * Casts one half of an Odoo many2one relation tuple onto a flat property.
 *
 * Odoo returns a relation as `[id, display_name]` (or `false`/`null` when empty).
 * Two properties read the same input key, each annotated with the part it wants:
 *
 *   #[MapInputName('project_id'), WithCast(OdooRelationCast::class, RelationPart::Id)]
 *   public ?int $projectId;
 *   #[MapInputName('project_id'), WithCast(OdooRelationCast::class, RelationPart::Name)]
 *   public ?string $projectName;
 */
class OdooRelationCast implements Cast
{
    public function __construct(
        private readonly RelationPart $part = RelationPart::Id,
    ) {}

    /**
     * @param  array<string, mixed>  $properties
     * @param  CreationContext<BaseData<mixed, mixed, array-key>>  $context
     */
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): int|string|null
    {
        if (! is_array($value)) {
            return null;
        }

        return match ($this->part) {
            RelationPart::Id => \is_int($id = data_get($value, 0)) ? $id : null,
            RelationPart::Name => \is_string($name = data_get($value, 1)) ? $name : null,
        };
    }
}
