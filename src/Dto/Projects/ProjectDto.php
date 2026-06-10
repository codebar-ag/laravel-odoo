<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Dto\Projects;

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
    ) {}

    /** @param array<array-key, mixed> $data */
    public static function fromArray(array $data): self
    {
        $partner = $data['partner_id'] ?? false;
        $user = $data['user_id'] ?? false;

        $partnerId = \is_array($partner) ? ($partner[0] ?? null) : null;
        $partnerName = \is_array($partner) ? ($partner[1] ?? null) : null;
        $userId = \is_array($user) ? ($user[0] ?? null) : null;
        $userName = \is_array($user) ? ($user[1] ?? null) : null;

        return new self(
            id: \is_int($v = $data['id'] ?? 0) ? $v : 0,
            name: \is_string($v = $data['name'] ?? '') ? $v : '',
            description: \is_string($v = $data['description'] ?? null) ? $v : null,
            partnerId: \is_int($partnerId) ? $partnerId : null,
            partnerName: \is_string($partnerName) ? $partnerName : null,
            userId: \is_int($userId) ? $userId : null,
            userName: \is_string($userName) ? $userName : null,
            dateStart: \is_string($v = $data['date_start'] ?? null) ? $v : null,
            date: \is_string($v = $data['date'] ?? null) ? $v : null,
        );
    }
}
