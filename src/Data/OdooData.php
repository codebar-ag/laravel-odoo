<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Data;

use Illuminate\Support\Str;
use ReflectionNamedType;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

/**
 * Base class for every Odoo read DTO.
 *
 * It centralises two Odoo-specific concerns so individual DTOs stay declarative:
 *
 *  1. Snake_case input mapping — Odoo returns `work_email`, the property is `workEmail`.
 *  2. The `false`-means-empty sentinel — Odoo returns boolean `false` for an unset
 *     scalar/relation field. Left untouched, laravel-data would coerce that into
 *     `""`/`0`/`0.0`; we convert it to `null` instead. `bool` properties are skipped so a
 *     real `false` (e.g. an archived record's `active` flag) survives, and `array`
 *     properties are skipped so their cast can turn `false` into `[]`.
 */
#[MapInputName(SnakeCaseMapper::class)]
abstract class OdooData extends Data
{
    /** @var array<class-string, array<string, mixed>> */
    private static array $falseReplacements = [];

    /**
     * Hydrate the DTO from a raw Odoo record.
     *
     * `fromArray` is laravel-data's own magical creation method, so it must be ignored
     * here — otherwise `from()` would dispatch straight back into the caller's
     * `fromArray()` and recurse infinitely.
     *
     * @param  array<array-key, mixed>  $data
     */
    protected static function hydrate(array $data): static
    {
        /** @var static $dto */
        $dto = static::factory()
            ->ignoreMagicalMethod('fromArray')
            ->from($data);

        return $dto;
    }

    /**
     * @param  array<array-key, mixed>  $properties
     * @return array<array-key, mixed>
     */
    public static function prepareForPipeline(array $properties): array
    {
        $replacements = self::falseReplacements();

        foreach ($properties as $key => $value) {
            if ($value === false && \array_key_exists($key, $replacements)) {
                $properties[$key] = $replacements[$key];
            }
        }

        return $properties;
    }

    /**
     * Map of snake_cased input key => the value a literal `false` should become, derived
     * from the constructor so each property keeps its original default (e.g. `priority`
     * → `'0'`, a nullable field → `null`, a required string → `''`).
     *
     * `bool` and `array` properties are excluded: a `bool` keeps a real `false`, and an
     * `array` lets its cast turn `false` into an empty list.
     *
     * @return array<string, mixed>
     */
    private static function falseReplacements(): array
    {
        if (isset(self::$falseReplacements[static::class])) {
            return self::$falseReplacements[static::class];
        }

        $constructor = (new \ReflectionClass(static::class))->getConstructor();
        $map = [];

        foreach ($constructor?->getParameters() ?? [] as $parameter) {
            $type = $parameter->getType();

            if (! $type instanceof ReflectionNamedType || \in_array($type->getName(), ['bool', 'array'], true)) {
                continue;
            }

            $key = Str::snake($parameter->getName());

            $map[$key] = match (true) {
                $parameter->isDefaultValueAvailable() => $parameter->getDefaultValue(),
                $type->allowsNull() => null,
                default => match ($type->getName()) {
                    'string' => '',
                    'int' => 0,
                    'float' => 0.0,
                    default => null,
                },
            };
        }

        return self::$falseReplacements[static::class] = $map;
    }
}
