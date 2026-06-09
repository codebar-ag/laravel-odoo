<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Dto\CallKw\Projects;

use Illuminate\Support\Arr;

readonly class ProjectDto
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $description,
        public ?int $partnerId,
        public ?string $partnerName,
        public ?int $userId,
        public ?string $userName,
        public ?string $dateStart,
        public ?string $date,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        $partner = Arr::get($data, 'partner_id');
        $user = Arr::get($data, 'user_id');

        return new self(
            id: \intval(Arr::get($data, 'id')),
            name: \strval(Arr::get($data, 'name') ?? ''),
            description: ($v = Arr::get($data, 'description')) ? \strval($v) : null,
            partnerId: \is_array($partner) ? \intval($partner[0]) : null,
            partnerName: \is_array($partner) ? \strval($partner[1] ?? '') : null,
            userId: \is_array($user) ? \intval($user[0]) : null,
            userName: \is_array($user) ? \strval($user[1] ?? '') : null,
            dateStart: ($v = Arr::get($data, 'date_start')) ? \strval($v) : null,
            date: ($v = Arr::get($data, 'date')) ? \strval($v) : null,
        );
    }
}
