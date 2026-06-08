<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Dto\Auth;

use Illuminate\Support\Arr;

readonly class AuthDto
{
    public function __construct(
        public ?int $uid,
        public ?string $sessionId,
        public ?string $db,
        public ?string $login,
        public ?bool $totpRequired,
    ) {}

    /** @param array<string, mixed> $data */
    public static function fromResponse(array $data): self
    {
        $totpRequired = Arr::get($data, 'totp_required');

        return new self(
            uid: ($v = Arr::get($data, 'uid')) !== null ? (int) $v : null,
            sessionId: ($v = Arr::get($data, 'session_id')) !== null ? (string) $v : null,
            db: ($v = Arr::get($data, 'db')) !== null ? (string) $v : null,
            login: ($v = Arr::get($data, 'login')) !== null ? (string) $v : null,
            totpRequired: is_bool($totpRequired) ? $totpRequired : null,
        );
    }
}
