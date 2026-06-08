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
            id: (int) Arr::get($data, 'id'),
            name: (string) Arr::get($data, 'name'),
            description: ($v = Arr::get($data, 'description')) ? (string) $v : null,
            partnerId: is_array($partner) ? (int) $partner[0] : null,
            partnerName: is_array($partner) ? (string) $partner[1] : null,
            userId: is_array($user) ? (int) $user[0] : null,
            userName: is_array($user) ? (string) $user[1] : null,
            dateStart: ($v = Arr::get($data, 'date_start')) ? (string) $v : null,
            date: ($v = Arr::get($data, 'date')) ? (string) $v : null,
        );
    }
}
