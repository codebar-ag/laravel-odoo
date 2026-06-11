<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Dto\Users;

readonly class UserDto
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $lang,
        public ?string $login,
        public ?string $email,
        public ?string $tz,
        public ?string $jobTitle,
        public ?bool $active,
        public ?bool $share,
        public ?int $partnerId,
        public ?string $partnerName,
        public ?int $companyId,
        public ?string $companyName,
        public ?int $employeeId,
        public ?string $employeeName,
    ) {}

    /** @param array<array-key, mixed> $data */
    public static function fromArray(array $data): self
    {
        $partnerId = data_get($data, 'partner_id.0');
        $partnerName = data_get($data, 'partner_id.1');
        $companyId = data_get($data, 'company_id.0');
        $companyName = data_get($data, 'company_id.1');
        $employeeId = data_get($data, 'employee_id.0');
        $employeeName = data_get($data, 'employee_id.1');
        $active = data_get($data, 'active');
        $share = data_get($data, 'share');

        return new self(
            id: \is_int($v = data_get($data, 'id', 0)) ? $v : 0,
            name: \is_string($v = data_get($data, 'name', '')) ? $v : '',
            lang: \is_string($v = data_get($data, 'lang')) ? $v : null,
            login: \is_string($v = data_get($data, 'login')) ? $v : null,
            email: \is_string($v = data_get($data, 'email')) ? $v : null,
            tz: \is_string($v = data_get($data, 'tz')) ? $v : null,
            jobTitle: \is_string($v = data_get($data, 'job_title')) ? $v : null,
            active: \is_bool($active) ? $active : null,
            share: \is_bool($share) ? $share : null,
            partnerId: \is_int($partnerId) ? $partnerId : null,
            partnerName: \is_string($partnerName) ? $partnerName : null,
            companyId: \is_int($companyId) ? $companyId : null,
            companyName: \is_string($companyName) ? $companyName : null,
            employeeId: \is_int($employeeId) ? $employeeId : null,
            employeeName: \is_string($employeeName) ? $employeeName : null,
        );
    }
}
