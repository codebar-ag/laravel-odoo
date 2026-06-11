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
        $partnerId = data_get($data, 'partner_id.0');
        $partnerName = data_get($data, 'partner_id.1');
        $userId = data_get($data, 'user_id.0');
        $userName = data_get($data, 'user_id.1');

        return new self(
            id: \is_int($v = data_get($data, 'id', 0)) ? $v : 0,
            name: \is_string($v = data_get($data, 'name', '')) ? $v : '',
            description: \is_string($v = data_get($data, 'description')) ? $v : null,
            partnerId: \is_int($partnerId) ? $partnerId : null,
            partnerName: \is_string($partnerName) ? $partnerName : null,
            userId: \is_int($userId) ? $userId : null,
            userName: \is_string($userName) ? $userName : null,
            dateStart: \is_string($v = data_get($data, 'date_start')) ? $v : null,
            date: \is_string($v = data_get($data, 'date')) ? $v : null,
        );
    }
}
