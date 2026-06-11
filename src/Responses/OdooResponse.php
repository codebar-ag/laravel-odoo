<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Responses;

use Saloon\Http\Response;

abstract class OdooResponse
{
    final protected function __construct(protected readonly Response $response) {}

    public static function fromResponse(Response $response): static
    {
        return new static($response);
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
        $message = $this->response->json('error_description')
            ?? $this->response->json('error.data.message')
            ?? $this->response->json('error.message');

        return is_string($message) ? $message : null;
    }

    public function errorCode(): ?string
    {
        if ($this->successful()) {
            return null;
        }
        $code = $this->response->json('error')
            ?? $this->response->json('error.code');

        return \is_string($code) ? $code : null;
    }

    public function body(): string
    {
        return $this->response->body();
    }
}
