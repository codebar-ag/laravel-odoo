<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Dto\Timer;

use CodebarAg\Odoo\Data\Casts\IntCast;
use CodebarAg\Odoo\Data\Casts\OdooRelationCast;
use CodebarAg\Odoo\Data\Enums\RelationPart;
use CodebarAg\Odoo\Data\OdooData;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;

class TimerDto extends OdooData
{
    public function __construct(
        #[WithCast(IntCast::class)]
        public int $id = 0,
        public string $displayName = '',
        public string $timerStart = '',
        public bool $timerPause = false,
        public bool $isTimerRunning = false,
        public string $resModel = '',
        public int $resId = 0,
        #[WithCast(OdooRelationCast::class, RelationPart::Id)]
        public ?int $userId = null,
        #[MapInputName('user_id'), WithCast(OdooRelationCast::class, RelationPart::Name)]
        public ?string $userName = null,
        public ?string $parentResModel = null,
        public int $parentResId = 0,
        #[WithCast(OdooRelationCast::class, RelationPart::Id)]
        public ?int $createUid = null,
        #[MapInputName('create_uid'), WithCast(OdooRelationCast::class, RelationPart::Name)]
        public ?string $createUidName = null,
        public ?string $createDate = null,
        #[WithCast(OdooRelationCast::class, RelationPart::Id)]
        public ?int $writeUid = null,
        #[MapInputName('write_uid'), WithCast(OdooRelationCast::class, RelationPart::Name)]
        public ?string $writeUidName = null,
        public ?string $writeDate = null,
    ) {}

    /** @param array<array-key, mixed> $data */
    public static function fromArray(array $data): self
    {
        return self::hydrate($data);
    }
}
