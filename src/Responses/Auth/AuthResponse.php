<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Responses\Auth;

use CodebarAg\Odoo\Dto\Session\Auth\AuthDto;
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

        $result = $this->response->json('result');

        if (! is_array($result)) {
            return null;
        }

        return AuthDto::fromResponse($result);
    }
}
