<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Data\Casts;

use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Contracts\BaseData;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

/**
 * Normalises an Odoo one2many/many2many id list (e.g. `user_ids`, `tag_ids`) into
 * a clean list of ints, dropping anything that is not an integer id.
 */
class IntListCast implements Cast
{
    /**
     * @param  array<string, mixed>  $properties
     * @param  CreationContext<BaseData<mixed, mixed, array-key>>  $context
     * @return array<int, int>
     */
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): array
    {
        if (! is_array($value)) {
            return [];
        }

        return collect($value)
            ->filter(fn ($id): bool => \is_int($id))
            ->values()
            ->all();
    }
}
