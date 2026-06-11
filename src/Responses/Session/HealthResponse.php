<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Responses\Session;

use CodebarAg\Odoo\Responses\OdooResponse;

class HealthResponse extends OdooResponse
{
    public function isHealthy(): bool
    {
        return $this->response->json('status') === 'pass';
    }
}
