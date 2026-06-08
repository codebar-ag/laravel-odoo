<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Responses;

use Saloon\Http\Response;

abstract class OdooResponse
{
    protected function __construct(protected readonly Response $response)
    {
    }

    public function successful(): bool
    {
        return $this->response->successful();
    }

    public function failed(): bool
    {
        return ! $this->successful();
    }

    public function status(): int
    {
        return $this->response->status();
    }

    public function error(): ?string
    {
        if ($this->successful()) {
            return null;
        }

        try {
            $message = $this->response->json('error.data.message')
                ?? $this->response->json('error.message');
        } catch (\JsonException) {
            return null;
        }

        return is_string($message) ? $message : null;
    }

    public function body(): string
    {
        return $this->response->body();
    }

    public function cookie(string $name): ?string
    {
        return $this->response->cookies()->getCookieByName($name)?->getValue();
    }

    public function header(string $name): ?string
    {
        return $this->response->header($name) ?: null;
    }

    public function errorCode(): ?string
    {
        if ($this->successful()) {
            return null;
        }

        try {
            $code = $this->response->json('error.code');
        } catch (\JsonException) {
            return null;
        }

        return $code !== null ? (string) $code : null;
    }

    public function sessionId(): ?string
    {
        return $this->cookie('session_id');
    }
}
