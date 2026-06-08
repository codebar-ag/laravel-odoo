<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Responses\Session;

use CodebarAg\Odoo\Responses\OdooResponse;
use Saloon\Http\Response;

class VersionResponse extends OdooResponse
{
    private function __construct(Response $response)
    {
        parent::__construct($response);
    }

    public static function fromResponse(Response $response): self
    {
        return new self($response);
    }

    public function serverVersion(): ?string
    {
        try {
            $v = $this->response->json('result.server_version');

            return is_string($v) ? $v : null;
        } catch (\JsonException) {
            return null;
        }
    }

    public function serie(): ?string
    {
        try {
            $v = $this->response->json('result.server_serie');

            return is_string($v) ? $v : null;
        } catch (\JsonException) {
            return null;
        }
    }
}
