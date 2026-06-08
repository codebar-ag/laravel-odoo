<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Dto\Session\Auth;

use Illuminate\Support\Arr;

readonly class PasskeyOptionsDto
{
    /**
     * @param  array<int, mixed>  $allowCredentials
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
            challenge: ($v = Arr::get($data, 'challenge')) !== null ? (string) $v : null,
            rpId: ($v = Arr::get($data, 'rpId')) !== null ? (string) $v : null,
            timeout: ($v = Arr::get($data, 'timeout')) !== null ? (int) $v : null,
            userVerification: ($v = Arr::get($data, 'userVerification')) !== null ? (string) $v : null,
            allowCredentials: is_array($v = Arr::get($data, 'allowCredentials')) ? $v : [],
        );
    }
}
