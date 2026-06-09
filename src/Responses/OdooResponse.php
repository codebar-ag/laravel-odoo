<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Responses;

use Saloon\Http\Response;

abstract class OdooResponse
{
    protected function __construct(protected readonly Response $response) {}

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

        $message = $this->response->json('error.data.message')
            ?? $this->response->json('error.message');

        return is_string($message) ? $message : null;
    }

    public function body(): string
    {
        return $this->response->body();
    }

    public function cookie(string $name): ?string
    {
        $header = $this->response->header('Set-Cookie');
        $cookies = \is_array($header) ? $header : (\is_string($header) ? [$header] : []);

        foreach ($cookies as $cookieStr) {
            if (! \is_string($cookieStr)) {
                continue;
            }
            [$nameVal] = explode(';', $cookieStr, 2);
            $parts = explode('=', $nameVal, 2);
            if (\count($parts) === 2 && \trim($parts[0]) === $name) {
                return \urldecode(\trim($parts[1]));
            }
        }

        return null;
    }

    public function header(string $name): ?string
    {
        $value = $this->response->header($name);

        if (\is_array($value)) {
            $value = $value[0] ?? null;
        }

        return \is_string($value) ? $value : null;
    }

    public function errorCode(): ?string
    {
        if ($this->successful()) {
            return null;
        }

        $code = $this->response->json('error.code');

        return $code !== null ? \strval($code) : null;
    }

    public function sessionId(): ?string
    {
        return $this->cookie('session_id');
    }
}
