<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Responses\Session;

use CodebarAg\Odoo\Responses\OdooResponse;
use Saloon\Http\Response;

class HealthResponse extends OdooResponse
{
    private function __construct(Response $response)
    {
        parent::__construct($response);
    }

    public static function fromResponse(Response $response): self
    {
        return new self($response);
    }

    public function isHealthy(): bool
    {
        return $this->response->json('status') === 'pass';
    }
}
