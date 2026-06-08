<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Dto\Auth;

readonly class AuthenticateDto
{
    public function __construct(
        public string $db,
        public string $login,
        public string $password,
    ) {}

    /** @return array<string, string> */
    public function toArray(): array
    {
        return [
            'db' => $this->db,
            'login' => $this->login,
            'password' => $this->password,
        ];
    }
}
