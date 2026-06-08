<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Dto\Auth;

readonly class Authenticate2FADto
{
    public function __construct(
        public string $totpToken,
    ) {
    }

    /** @return array<string, string> */
    public function toArray(): array
    {
        return [
            'totp_token' => $this->totpToken,
        ];
    }
}
