<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Dto\Auth;

readonly class AuthenticatePasskeyDto
{
    /**
     * @param  array<string, string>  $response  WebAuthn assertion response (clientDataJSON, authenticatorData, signature, userHandle)
     */
    public function __construct(
        public string $id,
        public string $rawId,
        public string $type,
        public array $response,
    ) {}

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'rawId' => $this->rawId,
            'type' => $this->type,
            'response' => $this->response,
        ];
    }
}
