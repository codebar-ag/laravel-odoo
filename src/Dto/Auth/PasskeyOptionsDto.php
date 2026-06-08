<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Dto\Auth;

readonly class PasskeyOptionsDto
{
    /**
     * @param array<int, mixed> $allowCredentials
     */
    public function __construct(
        public ?string $challenge,
        public ?string $rpId,
        public ?int $timeout,
        public ?string $userVerification,
        public array $allowCredentials,
    ) {}

    /** @param array<string, mixed> $data */
    public static function fromResponse(array $data): self
    {
        return new self(
            challenge: isset($data['challenge']) ? (string) $data['challenge'] : null,
            rpId: isset($data['rpId']) ? (string) $data['rpId'] : null,
            timeout: isset($data['timeout']) ? (int) $data['timeout'] : null,
            userVerification: isset($data['userVerification']) ? (string) $data['userVerification'] : null,
            allowCredentials: is_array($data['allowCredentials'] ?? null) ? $data['allowCredentials'] : [],
        );
    }
}
