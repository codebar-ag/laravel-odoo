<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Responses\Auth;

use CodebarAg\Odoo\Dto\Auth\PasskeyOptionsDto;
use CodebarAg\Odoo\Responses\OdooResponse;
use Saloon\Http\Response;

class PasskeyOptionsResponse extends OdooResponse
{
    private function __construct(Response $response)
    {
        parent::__construct($response);
    }

    public static function fromResponse(Response $response): self
    {
        return new self($response);
    }

    public function dto(): ?PasskeyOptionsDto
    {
        if ($this->failed()) {
            return null;
        }

        $data = $this->response->json();

        if (! is_array($data)) {
            return null;
        }

        return PasskeyOptionsDto::fromResponse($data);
    }
}
