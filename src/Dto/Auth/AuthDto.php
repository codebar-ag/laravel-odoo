<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Dto\Auth;

readonly class AuthDto
{
    public function __construct(
        public ?int $uid,
        public ?string $sessionId,
        public ?string $db,
        public ?string $login,
        public ?bool $totpRequired,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromResponse(array $data): self
    {
        $totpRequired = $data['totp_required'] ?? null;

        return new self(
            uid: isset($data['uid']) ? (int) $data['uid'] : null,
            sessionId: isset($data['session_id']) ? (string) $data['session_id'] : null,
            db: isset($data['db']) ? (string) $data['db'] : null,
            login: isset($data['login']) ? (string) $data['login'] : null,
            totpRequired: is_bool($totpRequired) ? $totpRequired : null,
        );
    }
}
