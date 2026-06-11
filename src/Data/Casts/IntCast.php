<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Data\Casts;

use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Contracts\BaseData;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

/**
 * Coerces a required integer field, falling back to 0 for any non-integer value.
 *
 * Mirrors the original DTO guard (`is_int($v) ? $v : 0`) so malformed Odoo payloads
 * never blow up hydration of a non-nullable `id`.
 */
class IntCast implements Cast
{
    /**
     * @param  array<string, mixed>  $properties
     * @param  CreationContext<BaseData<mixed, mixed, array-key>>  $context
     */
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): int
    {
        return \is_int($value) ? $value : 0;
    }
}
