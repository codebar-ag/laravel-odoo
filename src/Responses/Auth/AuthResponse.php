<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Responses\Auth;

use CodebarAg\Odoo\Dto\Auth\AuthDto;
use CodebarAg\Odoo\Responses\OdooResponse;
use Saloon\Http\Response;

class AuthResponse extends OdooResponse
{
    private function __construct(Response $response)
    {
        parent::__construct($response);
    }

    public static function fromResponse(Response $response): self
    {
        return new self($response);
    }

    public function dto(): ?AuthDto
    {
        if ($this->failed()) {
            return null;
        }

        $contentType = $this->response->header('Content-Type') ?? '';

        if (str_contains($contentType, 'text/html')) {
            return $this->dtoFromHtml($this->response->body());
        }

        $result = $this->response->json('result');

        if (! is_array($result)) {
            return null;
        }

        return AuthDto::fromResponse($result);
    }

    private function dtoFromHtml(string $html): ?AuthDto
    {
        $marker = 'odoo.__session_info__ = ';
        $start = strpos($html, $marker);

        if ($start === false) {
            return null;
        }

        $start += strlen($marker);
        $end = strpos($html, '};', $start);

        if ($end === false) {
            return null;
        }

        $data = json_decode(substr($html, $start, $end - $start + 1), true);

        if (! is_array($data)) {
            return null;
        }

        return new AuthDto(
            uid: isset($data['uid']) ? (int) $data['uid'] : null,
            sessionId: null,
            db: isset($data['db']) ? (string) $data['db'] : null,
            login: isset($data['username']) ? (string) $data['username'] : null,
            totpRequired: false,
        );
    }
}
